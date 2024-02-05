# Axio Studio Comuni

## Informazioni

Questo package Laravel include delle API utili a fruire delle informazioni riguardanti i comuni italiani.

## Come funziona

Grazie a comode API è possibile ottenere informazioni di CAP, città, provincie, regioni e zone d'Italia.

| Endpoint                     | Metodo | Descrizione                                                              | Parametri                                                            |
| ---------------------------- | ------ | ------------------------------------------------------------------------ | -------------------------------------------------------------------- |
| /api/comuni/zones            | GET    | Ritorna una lista di tutte le zone italiane                              | -                                                                    |
| /api/comuni/zones/{id}       | GET    | Ritorna le informazioni di una determinata zona in base al suo "id"      | -                                                                    |
| /api/comuni/regions          | GET    | Ritorna la lista di tutte le regioni italiane                            | -                                                                    |
| /api/comuni/regions/{id}     | GET    | Ritorna le informazioni di una determinata regione in base al suo "id"   | -                                                                    |
| /api/comuni/provinces        | GET    | Ritorna la lista di tutte le regioni italiane                            | q (querystring) - filtra per nome dopo il terzo carattere di ricerca |
| /api/comuni/provinces/{id}   | GET    | Ritorna le informazioni di una determinata regione in base al suo "id"   | -                                                                    |
| /api/comuni/provinces/{code} | GET    | Ritorna le informazioni di una determinata regione in base al suo "code" | -                                                                    |
| /api/comuni/cities           | GET    | Ritorna la lista di tutte le città italiane                              | q (querystring) - filtra per nome dopo il terzo carattere di ricerca |
| /api/comuni/cities/{id}      | GET    | Ritorna le informazioni di una determinata città in base al suo "id"     | -                                                                    |
| /api/comuni/zips             | GET    | Ritorna la lista di tutti i CAP italiani                                 | q (querystring) - filtra per codice (5 caratteri numerici)           |
| /api/comuni/zips/{id}        | GET    | Ritorna le informazioni di un determinato CAP in base al suo "id"        | -                                                                    |

## Installazione

Per installare il pacchetto è necessario aggiungere al file composer le seguenti istruzioni:

```
...
"repositories": [
    {
        "type": "vcs",
        "url": "https://gitlab.com/axio-studio-laravel-packages/comuni"
    }
],
"require": {
    ...
    "axiostudio/comuni": "dev-main",
    ...
```

Essendo un package privato dobbiamo quindi crearci un token di autenticazione su Gitlab (https://gitlab.com/-/profile/personal_access_tokens) stabilendo una data di scadenza futura e garantendo l'accesso a tutti i servizi cliccabili, una volta ottenuto il token lanciamo nel nostro progetto il seguente comando:

```bash
composer config --auth gitlab-token.gitlab.com TOKEN-PERSONALE
```

Noteremo che si creerà nel progetto un file chiamato auth.json.

ATTENZIONE! Il file auth.json è da impostare nel .gitignore (se non è già impostato) in quanto contiene token di accesso personali e sensibili.

Eseguire poi:

```bash
composer update
```

Per installare il package è necessario avviare le migration ed eseguire:

```bash
php artisan comuni:update
```

## Note

Questo package supporta Laravel 8, 9 e 10 e PHP dalla versione 8 in poi.
