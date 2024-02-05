<?php

namespace Axiostudio\Comuni\Commands;

use Axiostudio\Comuni\Models\City;
use Axiostudio\Comuni\Models\Province;
use Axiostudio\Comuni\Models\Region;
use Axiostudio\Comuni\Models\Zip;
use Axiostudio\Comuni\Models\Zone;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class UpdateDatabaseCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'comuni:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Aggiorna il database dei comuni';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Aggiornamento database comuni in corso...');

        $data = $this->getDataFromRemote();

        $this->truncateEntities();

        $this->seedZones($data);
        $this->seedRegions($data);
        $this->seedProvinces($data);
        $this->seedCities($data);
        $this->seedZips($data);

        $this->info('Aggiornamento database comuni completato!');
    }

    protected function cacheClear()
    {
        $this->info('Svuoto la cache...');
        $this->call('cache:clear');
    }

    protected function getDataFromRemote()
    {
        try {
            return json_decode(file_get_contents(config('comuni.data')));
        } catch (\Exception $e) {
            $this->error('Non è stato possibile recuperare il file remoto per i dati, aggiorna la configurazione del repository e riprova.');
        }
    }

    protected function truncateEntities()
    {
        $this->info('Elimino le vecchie informazioni a database...');

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('zones')->truncate();
        DB::table('regions')->truncate();
        DB::table('provinces')->truncate();
        DB::table('cities')->truncate();
        DB::table('zips')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }

    protected function formatId($code)
    {
        return (str_starts_with(0, $code)) ? substr($code, 1) : $code;
    }

    protected function seedZones($data)
    {
        $this->info('Ricreo il database Zone...');

        foreach ($data as $city) {
            Zone::firstOrCreate([
                'id' => $this->formatId($city->zona->codice),
                'name' => $city->zona->nome,
            ]);
        }
    }

    protected function seedRegions($data)
    {
        $this->info('Ricreo il database Regioni...');

        foreach ($data as $city) {
            Region::firstOrCreate([
                'id' => $this->formatId($city->regione->codice),
                'name' => $city->regione->nome,
                'zone_id' => $this->formatId($city->zona->codice),
            ]);
        }
    }

    protected function seedProvinces($data)
    {
        $this->info('Ricreo il database Provincie...');

        foreach ($data as $city) {
            Province::firstOrCreate([
                'id' => $this->formatId($city->provincia->codice),
                'name' => $city->provincia->nome,
                'code' => $city->sigla,
                'region_id' => $this->formatId($city->regione->codice),
            ]);
        }
    }

    protected function seedCities($data)
    {
        $this->info('Ricreo il database Città...');

        foreach ($data as $city) {
            City::firstOrCreate([
                'id' => $this->formatId($city->codice),
                'name' => $city->nome,
                'code' => $city->codiceCatastale,
                'province_id' => $this->formatId($city->provincia->codice),
            ]);
        }
    }

    protected function seedZips($data)
    {
        $this->info('Ricreo il database CAP...');

        foreach ($data as $city) {
            foreach ($city->cap as $zip) {
                Zip::create([
                    'code' => $zip,
                    'city_id' => $this->formatId($city->codice),
                ]);
            }
        }
    }
}
