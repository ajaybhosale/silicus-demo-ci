<?php

use Illuminate\Database\Seeder;
use App\User;
use Modules\Company\Models\Address;

class UsersTableSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $users = factory(User::class, 10)->create();
        $arrFakeCompany = factory(Address::class)->make()->toArray();
        $objCompanyResponse = $this->call('POST', 'companies', $arrFakeCompany);
    }
}
