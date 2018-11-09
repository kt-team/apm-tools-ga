### Сбор метрик с Google Analytics, таких как JS ошибки и  Конверсия (за прошедший день)

Для подключения необходимо:
1. Настроить доступ в Google Analytics  
    .....

2. Скачать скрипт в нужную директорию на сервере  
    `git clone https://github.com/kt-team/apm-tools-ga.git`

3. Произвести настройки скрипта  
    Настройки скрипта находятся в файле config.php.
    Описание основных параметров ниже.

4. Настроить запуск скрипта  
    Запуск скрипта можно настроить по средствам cron раз в день.  
    Например, `0 3 * * * php /srv/local/apm/apm-tools-ga/index.php`


##### Основные параметры скрипта:
- **$config['key_file_location']** - Путь до файла с реквизитами предоставленного сервисом GA
- **$config['license_key']** - Ключ выданный сервисом APMinvest
- **$config['metrics']['js-error']** - Настройки сбора JS ошибок с GA
    - **['ga']** - Настройки для GA
        - **['view_id']** - Номер представления в GA
        - **['dimension']** - Dimension в GA
        - **['event_filter']** - Event filter в GA
        - **['metric']** - Metrics в GA
    - **['apm']** - Настройки для APMinvest
        - **['metric_code']** - Код метрики в APMinvest
        - **['metric_label']** - Лейбл метрики в APMinvest
- **$config['metrics']['conversion']** - Настройки сбора Конверсии с GA
    - _Настройки аналогичны сбору JS ошибок_