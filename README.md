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

Per installare il package, eseguire:

```bash
composer require axiostudio/comuni-italiani
```

Per completare l'installazione è necessario avviare le migration ed eseguire:

```bash
php artisan migrate && php artisan comuni:update
```

## Personalizzazione

È possibile esportare nel proprio progetto il file config e le migrations del package tramite:

```bash
php artisan vendor:publish --provider="Axiostudio\Comuni\ComuniServiceProvider"
```

## Note

Per supporto o bug utilizzare le Issue di Github, per collaborare invece è sufficente aprire un PR con le specifiche dell'integrazione eseguita.

## Credits

Questo pacchetto è stato creato ed è mantenuto da Axio Studio, per maggiori informazioni: https://axio.studio.
