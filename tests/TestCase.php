<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Infusionsoft\InfusionsoftCollection;
use App\Adapters\InfusionsoftAdapter;
use Illuminate\Support\Facades\Artisan;
use App\User;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    /**
     * @var InfusionsoftCollection
     */
    protected $tagCollection;

    /**
     * @var User
     */
    protected $user;

    /**
     * InfusionSoft tag dummy data
     */
    protected function createTagCollection()
    {
        $jsonData = '[{"id":159,"name":"Clean List","description":"Email Clean List","category":{"id":15,"name":"Smart Lists","description":null}},{"id":161,"name":"Email Typos","description":"Likely Email Typos in Email Domain","category":{"id":15,"name":"Smart Lists","description":null}},{"id":154,"name":"Module reminders completed","description":null,"category":null},{"id":138,"name":"Start IAA Module 1 Reminders","description":"","category":null},{"id":140,"name":"Start IAA Module 2 Reminders","description":"","category":null},{"id":142,"name":"Start IAA Module 3 Reminders","description":"","category":null},{"id":144,"name":"Start IAA Module 4 Reminders","description":"","category":null},{"id":146,"name":"Start IAA Module 5 Reminders","description":"","category":null},{"id":148,"name":"Start IAA Module 6 Reminders","description":"","category":null},{"id":150,"name":"Start IAA Module 7 Reminders","description":"","category":null},{"id":124,"name":"Start IEA Module 1 Reminders","description":"","category":null},{"id":126,"name":"Start IEA Module 2 Reminders","description":"","category":null},{"id":128,"name":"Start IEA Module 3 Reminders","description":"","category":null},{"id":130,"name":"Start IEA Module 4 Reminders","description":"","category":null},{"id":132,"name":"Start IEA Module 5 Reminders","description":"","category":null},{"id":134,"name":"Start IEA Module 6 Reminders","description":"","category":null},{"id":136,"name":"Start IEA Module 7 Reminders","description":"","category":null},{"id":110,"name":"Start IPA Module 1 Reminders","description":"","category":null},{"id":112,"name":"Start IPA Module 2 Reminders","description":"","category":null},{"id":114,"name":"Start IPA Module 3 Reminders","description":"","category":null},{"id":116,"name":"Start IPA Module 4 Reminders","description":"","category":null},{"id":118,"name":"Start IPA Module 5 Reminders","description":"","category":null},{"id":120,"name":"Start IPA Module 6 Reminders","description":"","category":null},{"id":122,"name":"Start IPA Module 7 Reminders","description":"","category":null}]';

        $this->tagCollection = InfusionsoftCollection::make(json_decode($jsonData));
    }

    /**
     * InfusionSoft contact dummy data
     *
     * @param $user
     * @return array
     */
    protected function contactDummyData($user)
    {
        return [
            'Email' => $user->email,
            '_Products' => 'ipa,iea',
            'Id' => '5295',
        ];
    }

    /**
     * Set database, mocks and different cases in database
     *
     * @param $case
     * @param null $products
     * @throws \ReflectionException
     */
    public function setEnvironment($case, $products = null)
    {
        parent::setUp();

        Artisan::call('db:seed', ['--class' => 'iPSDevTestSeeder']);

        $this->user = factory(User::class ,$case)->create();

        $this->createTagCollection();

        $stub = $this->createMock(InfusionsoftAdapter::class);

        $stub->expects($this->any())
            ->method('tags')
            ->will($this->returnValue($this->tagCollection));

        if ($products === null) {
            $stub->expects($this->any())
                ->method('contacts')
                ->will($this->returnValue($this->contactDummyData($this->user)));
        } else {
            $contact = $this->contactDummyData($this->user);
            $contact['_Products'] = $products;

            $stub->expects($this->any())
                ->method('contacts')
                ->will($this->returnValue($contact));
        }

        $stub->expects($this->any())
            ->method('addTag')
            ->will($this->returnValue(true));

        app()->bind(InfusionsoftAdapter::class, function() use ($stub) {
            return $stub;
        });
    }
}
