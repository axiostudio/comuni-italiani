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
        $this->newLine(2);

        $this->seedZones();
        $this->seedRegions();
        $this->seedProvinces();
        $this->seedCities();
        $this->seedZips();

        $this->cacheClear();

        // $this->line('Aggiornamento database comuni completato!');
    }

    protected function cacheClear()
    {
        $this->line('Svuoto la cache...');
        $this->call('cache:clear');
    }

    protected function getDataFromRemote($key = 'comuni.data')
    {
        $url = config($key);
        // $this->line('Recupero i dati da '.$url.'...');
        
        $data = file_get_contents($url);

        try {
            return json_decode($data);
        } catch (\Exception $e) {
            $this->error('Non è stato possibile recuperare il file remoto per i dati, aggiorna la configurazione del repository e riprova.');
        }
    }

    protected function formatId($code)
    {
        return ltrim($code, '0');
    }

    protected function getProvinceCodeByProvinceLabel($data = [], $label = '')
    {

        $code = '';
        foreach ($data as $province) {
            if (strtolower($province->sigla_provincia) == strtolower($label)) {
                $code = $province->codice_sovracomunale;
                break;
            }
        }

        return $code;
    }

    protected function seedZones()
    {
        $this->line('Ricreo il database Zone...');
        $zones = $this->getDataFromRemote('comuni.import.zone_data_file');
        
        $bar = $this->output->createProgressBar(count($zones));

        foreach ($zones as $zone) {
            Zone::updateOrCreate([
                'id' => $this->formatId($zone->id),
                'name' => $zone->name,
            ]);

            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);
        // $this->line('Database Zone completato!');
    }

    protected function seedRegions()
    {
        $this->line('Ricreo il database Regioni...');
        $regions = $this->getDataFromRemote('comuni.import.regioni_data_file');

        $bar = $this->output->createProgressBar(count($regions));

        foreach ($regions as $region) {
            $zone = Zone::where('name', $region->ripartizione_geografica)->firstOrFail();

            Region::updateOrCreate([
                'id' => $this->formatId($region->codice_regione),
                'name' => $region->denominazione_regione,
                'zone_id' => $this->formatId($zone->id),
            ]);

            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);
        // $this->line('Database Regioni completato!');
    }

    protected function seedProvinces()
    {
        $this->line('Ricreo il database Provincie...');
        $provinces = $this->getDataFromRemote('comuni.import.province_data_file');

        $bar = $this->output->createProgressBar(count($provinces));

        foreach ($provinces as $province) {
            Province::updateOrCreate([
                'id' => $this->formatId($province->codice_sovracomunale),
                'name' => $province->denominazione_provincia,
                'code' => $province->sigla_provincia,
                'region_id' => $this->formatId($province->codice_regione),
            ]);

            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);
        // $this->line('Database Provincie completato!');
    }

    protected function seedCities()
    {
        $this->line('Ricreo il database Città...');
        $cities = $this->getDataFromRemote('comuni.import.comuni_data_file');

        $bar = $this->output->createProgressBar(count($cities));

        foreach ($cities as $city) {
            $province = Province::where('code', $city->sigla_provincia)->firstOrFail();
           
            City::updateOrCreate([
                'id' => $this->formatId($city->codice_istat),
                'name' => $city->denominazione_ita,
                'code' => $city->codice_belfiore,
                'province_id' => $this->formatId($province->id),
            ]);

            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);
        // $this->line('Database Città completato!');
    }

    protected function seedZips()
    {
        $this->line('Ricreo il database CAP...');
        $zips = $this->getDataFromRemote('comuni.import.zip_data_file');

        $bar = $this->output->createProgressBar(count($zips));

        foreach ($zips as $zip) {
            Zip::updateOrCreate([
                'code' => $zip->cap,
                'city_id' => $this->formatId($zip->codice_istat),
            ]);

            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);
        // $this->line('Database CAP completato!');
    }
}
