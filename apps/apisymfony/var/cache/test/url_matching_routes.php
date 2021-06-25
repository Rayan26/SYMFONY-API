<?php

/**
 * This file has been auto-generated
 * by the Symfony Routing Component.
 */

return [
    false, // $matchHost
    [ // $staticRoutes
        '/api/getQuestions' => [[['_route' => 'api_get_question', '_controller' => 'App\\Controller\\ApiPostController::questions'], null, ['GET' => 0], null, false, false, null]],
        '/api/postQuestion' => [[['_route' => 'api_post_question', '_controller' => 'App\\Controller\\ApiPostController::insert'], null, ['POST' => 0], null, false, false, null]],
        '/api/updateQuestion' => [[['_route' => 'api_update_question', '_controller' => 'App\\Controller\\ApiPostController::update'], null, ['POST' => 0], null, false, false, null]],
        '/api/historicCSV' => [[['_route' => 'api_historic_CSV', '_controller' => 'App\\Controller\\ApiPostController::getCSV'], null, ['GET' => 0], null, false, false, null]],
        '/api/entityCSV' => [[['_route' => 'api_entity_CSV', '_controller' => 'App\\Controller\\ApiPostController::getEntityCSV'], null, ['GET' => 0], null, false, false, null]],
    ],
    [ // $regexpList
        0 => '{^(?'
                .'|/api(?'
                    .'|/entityCSV/([^/]++)(*:33)'
                    .'|(?:/(index)(?:\\.([^/]++))?)?(*:68)'
                    .'|/(?'
                        .'|docs(?:\\.([^/]++))?(*:98)'
                        .'|contexts/(.+)(?:\\.([^/]++))?(*:133)'
                    .')'
                .')'
            .')/?$}sDu',
    ],
    [ // $dynamicRoutes
        33 => [[['_route' => 'api_CSV_entity', '_controller' => 'App\\Controller\\ApiPostController::getCSVEntity'], ['entity_name'], ['GET' => 0], null, false, true, null]],
        68 => [[['_route' => 'api_entrypoint', '_controller' => 'api_platform.action.entrypoint', '_format' => '', '_api_respond' => 'true', 'index' => 'index'], ['index', '_format'], null, null, false, true, null]],
        98 => [[['_route' => 'api_doc', '_controller' => 'api_platform.action.documentation', '_format' => '', '_api_respond' => 'true'], ['_format'], null, null, false, true, null]],
        133 => [
            [['_route' => 'api_jsonld_context', '_controller' => 'api_platform.jsonld.action.context', '_format' => 'jsonld', '_api_respond' => 'true'], ['shortName', '_format'], null, null, false, true, null],
            [null, null, null, null, false, false, 0],
        ],
    ],
    null, // $checkCondition
];
