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

        $this->truncateEntities();

        $this->seedZones();
        $this->seedRegions();
        $this->seedProvinces();
        $this->seedCities();
        $this->seedZips();

        $this->info('Aggiornamento database comuni completato!');
    }

    protected function cacheClear()
    {
        $this->info('Svuoto la cache...');
        $this->call('cache:clear');
    }

    protected function getDataFromRemote($key = 'comuni.data')
    {
        $url = config($key);
        $this->info('Recupero i dati da ' . $url . '...');
        $data = file_get_contents($url);
        try {
            return json_decode($data);
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

    protected function getZoneIdByName($name = '')
    {
        $groupZones = config('comuni.import.regions_groups');
        $id         = '';
        foreach ($groupZones as $key => $value) {
            if (strtolower($value) == strtolower($name)) {
                $id = $key;
                break;
            }
        }
        return $id;
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
        $this->info('Ricreo il database Zone...');
        $regionGroups = config('comuni.import.regions_groups');

        foreach ($regionGroups as $code => $name) {
            Zone::firstOrCreate([
                'id'   => $this->formatId($code),
                'name' => $name,
            ]);
        }
        $this->info('Database Zone completato!');
    }

    protected function seedRegions()
    {
        $this->info('Ricreo il database Regioni...');
        $regionsData = $this->getDataFromRemote('comuni.import.regioni_data_file');

        foreach ($regionsData as $city) {
            $zoneId = $this->getZoneIdByName($city->ripartizione_geografica);
            Region::firstOrCreate([
                'id'      => $this->formatId($city->codice_regione),
                'name'    => $city->denominazione_regione,
                'zone_id' => $this->formatId($zoneId),
            ]);
        }
        $this->info('Database Regioni completato!');
    }

    protected function seedProvinces()
    {
        $this->info('Ricreo il database Provincie...');
        $provincesData = $this->getDataFromRemote('comuni.import.province_codes_file');

        foreach ($provincesData as $city) {
            Province::firstOrCreate([
                'id'        => $this->formatId($city->codice_sovracomunale),
                'name'      => $city->denominazione_provincia,
                'code'      => $city->sigla_provincia,
                'region_id' => $this->formatId($city->codice_regione),
            ]);
        }
        $this->info('Database Provincie completato!');
    }

    protected function seedCities()
    {
        $this->info('Ricreo il database Città...');
        $citiesData    = $this->getDataFromRemote('comuni.import.comuni_data_file');
        $provincesData = $this->getDataFromRemote('comuni.import.province_codes_file');

        foreach ($citiesData as $city) {
            $provinceId = $this->getProvinceCodeByProvinceLabel($provincesData, $city->sigla_provincia);
            City::firstOrCreate([
                'id'          => $this->formatId($city->codice_istat),
                'name'        => $city->denominazione_ita,
                'code'        => $city->codice_belfiore,
                'province_id' => $this->formatId($provinceId),
            ]);
        }
        $this->info('Database Città completato!');
    }

    protected function seedZips()
    {
        $this->info('Ricreo il database CAP...');
        $data = $this->getDataFromRemote('comuni.import.zip_codes_file');

        foreach ($data as $city) {
            Zip::create([
                'code'    => $city->cap,
                'city_id' => $this->formatId($city->codice_istat),
            ]);
        }
        $this->info('Database CAP completato!');
    }
}
