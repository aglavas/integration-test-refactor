<?php

namespace App\Http\Helpers;

use App\Adapters\InfusionsoftAdapter;
use App\Module;
use App\ModuleTagId;
use Infusionsoft;
use Log;
use Storage;
use Request;


class InfusionsoftHelper
{
    private $infusionsoft;

    /**
     * InfusionsoftHelper constructor.
     * @param InfusionsoftAdapter $infusionsoft
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function __construct(InfusionsoftAdapter $infusionsoft)
    {
        $this->infusionsoft = $infusionsoft;

        if (Storage::exists('inf_token')) {

            Infusionsoft::setToken(unserialize(Storage::get("inf_token")));

        } else {
            Log::error("Infusionsoft token not set.");
        }
    }

    public function authorize(){
        if (Request::has('code')) {
            Infusionsoft::requestAccessToken(Request::get('code'));

            Storage::put('inf_token', serialize(Infusionsoft::getToken()));
            Log::notice('Infusionsoft token created');

            Infusionsoft::setToken(unserialize(Storage::get("inf_token")));

            return 'Success';
        }

        return '<a href="' . Infusionsoft::getAuthorizationUrl() . '">Authorize Infusionsoft</a>';
    }

    /**
     * Save tags to DB
     *
     * @param Infusionsoft\InfusionsoftCollection $infusionTags
     * @param ModuleTagId $moduleTagId
     * @param Module $module
     */
    private function saveTags(Infusionsoft\InfusionsoftCollection $infusionTags, ModuleTagId $moduleTagId, Module $module)
    {
        foreach ($infusionTags->all() as $tag) {
            if ($tag->name == 'Module reminders completed') {
                $moduleTagId->create([
                    'module_id' => null,
                    'infusion_id' => $tag->id,
                    'completed' => 1
                ]);
            } elseif (multi_strpos($tag->name, ['IAA', 'IPA', 'IEA']) !== false) {
                $expodedName = explode(' ', $tag->name);

                /** @var Module $module */
                $module = $module->where('course_key', strtolower($expodedName[1]))->where('module_number', $expodedName[3])->first();

                $module->infusionId()->create(['infusion_id' => $tag->id]);
            }
        }
    }

    /**
     * Get all tags from Infusion
     *
     * @param ModuleTagId $moduleTagId
     * @param Module $module
     * @return Module[]|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     * @throws \Exception
     */
    public function getAllTags(ModuleTagId $moduleTagId, Module $module)
    {
        $moduleTagIds = $moduleTagId->get();

        if ($moduleTagIds->count() === 0) {
            try {
                $infusionTags = $this->infusionsoft->tags();
                $this->saveTags($infusionTags, $moduleTagId, $module);
            } catch (\Exception $e){
                Log::error((string) $e);
                throw new \Exception('Infusionsoft connection problem.');
            }
        }

        return $moduleTagIds = $module->with('infusionId')->get();
    }

    public function getContact($email)
    {

        $fields = [
            'Id',
            'Email',
            'Groups',
            "_Products"
        ];

        try {
            return $this->infusionsoft->contacts($email, $fields);
        } catch (\Exception $e){
            Log::error((string) $e);
            throw new \Exception('Infusionsoft connection problem.');
        }
    }

    public function addTag($contact_id, $tag_id){
        try {
            return $this->infusionsoft->addTag($contact_id, $tag_id);

        } catch (\Exception $e){
            Log::error((string) $e);
            return false;
        }
    }

    public function createContact($data){

        try {
            return Infusionsoft::contacts('xml')->add($data);

        } catch (\Exception $e){
            Log::error((string) $e);
            return false;
        }
    }


}
