<?php

use \App\User;
use \Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /** Sample login */
    const SAMPLE_LOGIN = '18551633993';

    /** Sample password */
    const SAMPLE_PASSWORD = 'password';

	/**
	 * Seeds the table.
	 *
	 * @return void
	 */
	public function run()
	{
        (new User([
            'name'     => 'John Dow',
            'email'    => "sagiller@163.com",
            'phone'    => "18551633993",
            'password' => self::SAMPLE_PASSWORD,
            'api_token' => 'dwjfiejwfiejofjgojwgoejfwfewff',
        ]))->save();
	}
}
