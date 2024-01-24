<?php

$configs = [];

if ( null !== getenv('COCKPIT_APP_NAME') ) {
    $configs['app.name'] = getenv('COCKPIT_APP_NAME');
};

if ( null !== getenv('COCKPIT_SESSION') ) {
    $configs['session.name'] = getenv('COCKPIT_SESSION');
};

if ( null !== getenv('COCKPIT_SEC_KEY') ) {
    $configs['sec-key'] = getenv('COCKPIT_SEC_KEY');
};

if ( null !== getenv('COCKPIT_SITE_URL') ) {
    $configs['site_url'] = getenv('COCKPIT_SITE_URL');
};

if (
    null !== getenv('COCKPIT_DATABASE_SERVER') && 
    null !== getenv('COCKPIT_DATABASE_NAME') 
) {
    $configs['database'] = [
        "server"  => getenv('COCKPIT_DATABASE_SERVER'),
        "options" => [
            "db" => getenv('COCKPIT_DATABASE_NAME')
        ]
    ];
};

if ( null !== getenv('COCKPIT_REDIS_SERVER') ) {
    $configs['memory'] = [
        'server' => getenv('COCKPIT_REDIS_SERVER')
    ];
}

if (
    null !== getenv('COCKPIT_MAILER_FROM') && 
    null !== getenv('COCKPIT_MAILER_HOST') && 
    null !== getenv('COCKPIT_MAILER_USER') && 
    null !== getenv('COCKPIT_MAILER_PASSWORD') 
) {
    $configs['mailer'] = [
        "from"      => getenv('COCKPIT_MAILER_FROM'),
        "transport" => getenv('COCKPIT_MAILER_METHOD') ?: 'smtp',
        "host"      => getenv('COCKPIT_MAILER_HOST'),
        "user"      => getenv('COCKPIT_MAILER_USER'),
        "password"  => getenv('COCKPIT_MAILER_PASSWORD'),
        "port"      => getenv('COCKPIT_MAILER_PORT') ?: 587,
        "auth"      => getenv('COCKPIT_MAILER_AUTH') ?: true,
        "encryption"=> getenv('COCKPIT_MAILER_ENCRYPTION') ?: ''
    ];
};

if ( null !== getenv('COCKPIT_ALLOWED_ASSETS') ) {
    $configs['assets'] = [
        'asslowed_assets' => getenv('COCKPIT_ALLOWED_ASSETS')
    ];
};

if ( null !== getenv('COCKPIT_CORS_HEADERS') ) {
    $hlist = explode(',', getenv('COCKPIT_CORS_HEADERS'));
    foreach ($hlist as $value) {
        $parts = explode(" ", $value);
        $configs['cors'][$parts[0]] = $parts[1];
    };
};

if ( null !== getenv('COCKPIT_I18N') ) {
    $configs['i18n'] = getenv('COCKPIT_I18N');
}

return $configs;