<?php

namespace Database\Seeders;

use App\Models\Province;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProvinceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $provinces = [
            ['East Azerbaijan', '041'],
            ['West Azerbaijan', '044'],
            ['Ardabil', '045'],
            ['Isfahan', '031'],
            ['Alborz', '026'],
            ['Ilam', '084'],
            ['Bushehr', '077'],
            ['Tehran', '021'],
            ['Chaharmahal and Bakhtiari', '038'],
            ['South Khorasan', '056'],
            ['Razavi Khorasan', '051'],
            ['North Khorasan', '058'],
            ['Khuzestan', '061'],
            ['Zanjan', '024'],
            ['Semnan', '023'],
            ['Sistan and Baluchestan', '054'],
            ['Fars', '071'],
            ['Qazvin', '028'],
            ['Qom', '025'],
            ['Kurdistan', '087'],
            ['Kerman', '034'],
            ['Kermanshah', '083'],
            ['Kohgiluyeh and Boyer-Ahmad', '074'],
            ['Golestan', '017'],
            ['Lorestan', '066'],
            ['Gilan', '013'],
            ['Mazandaran', '011'],
            ['Markazi', '086'],
            ['Hormozgan', '076'],
            ['Hamadan', '081'],
            ['Yazd', '035'],
        ];


        foreach ($provinces as $province) {
            $data[] = [
                'name' => $province[0],
                'tel_prefix' => $province[1],
                'slug' => Str::slug($province[0]),
                'created_at'=>Carbon::now()
            ];
        }
        Province::insert($data);
    }
}
