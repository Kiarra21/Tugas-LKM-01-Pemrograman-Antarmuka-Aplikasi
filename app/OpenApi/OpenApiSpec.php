<?php

namespace App\OpenApi;

use OpenApi\Attributes as OA;

#[OA\Info(
	version: '1.0.0',
	title: 'Tugas LKM 01 PAA',
	description: 'Dokumentasi endpoint API Tugas LKM 01'
)]
#[OA\Server(
	url: '/',
	description: 'Current origin server'
)]
#[OA\SecurityScheme(
    securityScheme: 'bearerAuth',
    type: 'http',
    scheme: 'bearer',
    bearerFormat: 'JWT',
    description: 'Masukkan token JWT dengan format: Bearer {token}'
)]
class OpenApiSpec
{
}
