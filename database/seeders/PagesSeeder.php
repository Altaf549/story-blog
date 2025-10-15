<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Page;

class PagesSeeder extends Seeder
{
	public function run(): void
	{
		$defaults = [
			'privacy_policy' => 'Privacy Policy',
			'terms_and_conditions' => 'Terms & Conditions',
			'about_us' => 'About Us',
			'contact_us' => 'Contact Us',
		];

		foreach ($defaults as $key => $title) {
			Page::firstOrCreate(
				['key' => $key],
				['title' => $title, 'content' => null]
			);
		}
	}
}


