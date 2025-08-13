<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\LabelTemplate;

class LabelTemplateSeeder extends Seeder {
    public function run(): void {
        $defaults = [
            'keys' => [
                'organic' => 'Organic',
                'title' => 'COCONUT SUGAR',
                'lot' => '-', 'lcs' => '-', 'prod' => '-', 'best' => '-',
                'ing' => 'ORGANIC COCONUT NECTAR',
                'weight' => '20kg (4 x 5kg) / 44.1 lbs (4 x 11 lbs)',
                'imported' => "Company\nStreet\nCity, ZIP\nCountry",
                'manufactured' => "Company\nStreet\nCity, ZIP\nCountry",
                'attributeBox' => 'ATTRIBUTES / NOTES',
                'store' => 'STORE IN A COOL AND DRY PLACE',
                'export' => '* FOR EXPORT ONLY *',
            ],
            'badges' => [1=>null,2=>null,3=>null,4=>null],
            'theme'  => ['green'=>'#2e7d32','amber'=>'#f59e0b','paper'=>'#ffffff']
        ];

        LabelTemplate::updateOrCreate(
            ['slug'=>'13x14'],
            [
                'name'=>'13x14 cm (Portrait)',
                'width_cm'=>13,
                'height_cm'=>14,
                'orientation'=>'portrait',
                'defaults'=>json_encode($defaults)
            ]
        );

        LabelTemplate::updateOrCreate(
            ['slug'=>'14_5x10'],
            [
                'name'=>'14.5x10 cm (Landscape)',
                'width_cm'=>14.5,
                'height_cm'=>10,
                'orientation'=>'landscape',
                'defaults'=>json_encode($defaults)
            ]
        );
    }
}
