<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

use App\Models\Prefecture;

class PrefecturesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // prefecturesテーブルをクリア
        DB::table('prefectures')->truncate();

        //都道府県データを作成
        $prefectures = [
            ['id' => '01', 'prefecture_name' => '北海道'],
            ['id' => '02', 'prefecture_name' => '青森県'],
            ['id' => '03', 'prefecture_name' => '岩手県'],
            ['id' => '04', 'prefecture_name' => '宮城県'],
            ['id' => '05', 'prefecture_name' => '秋田県'],
            ['id' => '06', 'prefecture_name' => '山形県'],
            ['id' => '07', 'prefecture_name' => '福島県'],
            ['id' => '08', 'prefecture_name' => '茨城県'],
            ['id' => '09', 'prefecture_name' => '栃木県'],
            ['id' => '10', 'prefecture_name' => '群馬県'],
            ['id' => '11', 'prefecture_name' => '埼玉県'],
            ['id' => '12', 'prefecture_name' => '千葉県'],
            ['id' => '13', 'prefecture_name' => '東京都'],
            ['id' => '14', 'prefecture_name' => '神奈川県'],
            ['id' => '15', 'prefecture_name' => '新潟県'],
            ['id' => '16', 'prefecture_name' => '富山県'],
            ['id' => '17', 'prefecture_name' => '石川県'],
            ['id' => '18', 'prefecture_name' => '福井県'],
            ['id' => '19', 'prefecture_name' => '山梨県'],
            ['id' => '20', 'prefecture_name' => '長野県'],
            ['id' => '21', 'prefecture_name' => '岐阜県'],
            ['id' => '22', 'prefecture_name' => '静岡県'],
            ['id' => '23', 'prefecture_name' => '愛知県'],
            ['id' => '24', 'prefecture_name' => '三重県'],
            ['id' => '25', 'prefecture_name' => '滋賀県'],
            ['id' => '26', 'prefecture_name' => '京都府'],
            ['id' => '27', 'prefecture_name' => '大阪府'],
            ['id' => '28', 'prefecture_name' => '兵庫県'],
            ['id' => '29', 'prefecture_name' => '奈良県'],
            ['id' => '30', 'prefecture_name' => '和歌山県'],
            ['id' => '31', 'prefecture_name' => '鳥取県'],
            ['id' => '32', 'prefecture_name' => '島根県'],
            ['id' => '33', 'prefecture_name' => '岡山県'],
            ['id' => '34', 'prefecture_name' => '広島県'],
            ['id' => '35', 'prefecture_name' => '山口県'],
            ['id' => '36', 'prefecture_name' => '徳島県'],
            ['id' => '37', 'prefecture_name' => '香川県'],
            ['id' => '38', 'prefecture_name' => '愛媛県'],
            ['id' => '39', 'prefecture_name' => '高知県'],
            ['id' => '40', 'prefecture_name' => '福岡県'],
            ['id' => '41', 'prefecture_name' => '佐賀県'],
            ['id' => '42', 'prefecture_name' => '長崎県'],
            ['id' => '43', 'prefecture_name' => '熊本県'],
            ['id' => '44', 'prefecture_name' => '大分県'],
            ['id' => '45', 'prefecture_name' => '宮崎県'],
            ['id' => '46', 'prefecture_name' => '鹿児島県'],
            ['id' => '47', 'prefecture_name' => '沖縄県'],
        ];

        foreach($prefectures as $prefecture) {
            Prefecture::create($prefecture);
        }
    }
}
