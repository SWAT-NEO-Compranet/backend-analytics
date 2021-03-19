# Lista de seed para la base de datos

Lista completa de contratos

```
\encoding UTF8 \copy public.contracts (acronyms, institution, uc_name, responsible_uc, folder_title, published_at, opened_at, contract_type, procedure_type, contract_code, contract_title, contract_init_date, contract_finish_date, contract_amount, currency, contract_status, provider, company_size, rfc, rfc_verification, address_ad, dataset_origin) FROM '<path>' DELIMITER ',' CSV HEADER;
```

Contratos por dependencía

```
\encoding UTF8 \copy contract_by_dependences (acronym, institution_distinct,count,rank) FROM '<path>' DELIMITER ',' CSV HEADER;
```

Contratos por duración promedio

```
\encoding UTF8 \copy dependence_durations (acronym, average, count) FROM '<path>' DELIMITER ',' CSV HEADER;
```

Montos por dependencia

```
 \encoding UTF8 \copy amounts_per_dependencies (acronym, currency, date, import, counter) FROM '<path>' DELIMITER ',' CSV HEADER;
```

Tipos de procedimiento

```
 \encoding UTF8 \copy procedure_types (type, count) from 'C:/Users/jcmexdev/Downloads/etl/Contratos2013-2021_prepared_by_Tipo_de_procedimiento_sorted.csv' DELIMITER ',' CSV HEADER;
```
