<?php

use Axiostudio\Comuni\Models\City;
use Axiostudio\Comuni\Models\Province;
use Axiostudio\Comuni\Models\Region;
use Axiostudio\Comuni\Models\Zip;
use Axiostudio\Comuni\Models\Zone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

Route::group(['middleware' => ['api'], 'prefix' => config('comuni.route')], function () {
    Route::middleware(config('comuni.middlewares'))->get('/zones', function () {
        return Cache::remember('zones', config('comuni.ttl'), function () {
            return Zone::orderBy('name', 'asc')->get();
        });
    });

    Route::middleware(config('comuni.middlewares'))->get('/zones/{id}', function ($id) {
        return Cache::remember('zones-' . $id, config('comuni.ttl'), function () use ($id) {
            return Zone::where('id', $id)->with(['regions', 'regions.provinces', 'regions.provinces.cities', 'regions.provinces.cities.zips'])->firstOrFail();
        });
    })->whereNumber('id');

    Route::middleware(config('comuni.middlewares'))->get('/regions', function () {
        return Cache::remember('regions', config('comuni.ttl'), function () {
            return Region::orderBy('name', 'asc')->get();
        });
    });

    Route::middleware(config('comuni.middlewares'))->get('/regions/{id}', function ($id) {
        return Cache::remember('regions-' . $id, config('comuni.ttl'), function () use ($id) {
            return Region::where('id', $id)->with(['zone', 'provinces', 'provinces.cities', 'provinces.cities.zips'])->firstOrFail();
        });
    })->whereNumber('id');

    Route::middleware(config('comuni.middlewares'))->get('/provinces', function (Request $request) {
        if ($request->has('q') && Str::length($request->q) > 3) {
            return Province::where('name', 'like', '%' . $request->q . '%')->orderby('name', 'asc')->get();
        }

        return Cache::remember('provinces', config('comuni.ttl'), function () {
            return Province::orderBy('name', 'asc')->get();
        });
    });

    Route::middleware(config('comuni.middlewares'))->get('/provinces/{code}', function ($code) {
        return Cache::remember('provinces-' . $code, config('comuni.ttl'), function () use ($code) {
            return Province::where('code', $code)->with(['region', 'cities', 'cities.zips', 'region.zone'])->firstOrFail();
        });
    })->whereAlpha('code');

    Route::middleware(config('comuni.middlewares'))->get('/provinces/{id}', function ($id) {
        return Cache::remember('provinces-' . $id, config('comuni.ttl'), function () use ($id) {
            return Province::where('id', $id)->with(['region', 'cities', 'cities.zips', 'region.zone'])->firstOrFail();
        });
    })->whereNumber('id');

    Route::middleware(config('comuni.middlewares'))->get('/cities', function (Request $request) {
        if ($request->has('q') && Str::length($request->q) > 3) {
            return City::where('name', 'like', '%' . $request->q . '%')->orderby('name', 'asc')->get();
        }

        return Cache::remember('cities', config('comuni.ttl'), function () {
            return City::orderBy('name', 'asc')->get();
        });
    });

    Route::middleware(config('comuni.middlewares'))->get('/cities/{id}', function ($id) {
        return Cache::remember('cities-' . $id, config('comuni.ttl'), function () use ($id) {
            return City::where('id', $id)->with(['province', 'zips', 'province.region', 'province.region.zone'])->firstOrFail();
        });
    })->whereNumber('id');

    Route::middleware(config('comuni.middlewares'))->get('/zips', function (Request $request) {
        if ($request->has('q') && Str::length($request->q) == 5) {
            return Zip::where('code', 'like', $request->q . '%')->with('city', 'city.province', 'city.province.region', 'city.province.region.zone')->orderby('code', 'asc')->get();
        }

        return Cache::remember('zips', config('comuni.ttl'), function () {
            return Zip::orderBy('code', 'asc')->get();
        });
    });

    Route::middleware(config('comuni.middlewares'))->get('/zips/{id}', function ($id) {
        return Cache::remember('zips-' . $id, config('comuni.ttl'), function () use ($id) {
            return Zip::where('id', $id)->with(['city', 'city.province', 'city.province.region', 'city.province.region.zone'])->firstOrFail();
        });
    })->whereNumber('id');
});
