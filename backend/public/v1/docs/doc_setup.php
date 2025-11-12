<?php

/**
 * @OA\Info(
 *   title="API",
 *   description="Web programming API",
 *   version="1.0",
 *   @OA\Contact(
 *     email="daniel.tomic@stu.ibu.edu.ba",
 *     name="Daniel Tomic"
 *   )
 * )
 * @OA\Server(
 *     url=LOCALSERVER,
 *     description="API server"
 * )
 * @OA\Server(
 *     url=PRODSERVER,
 *     description="API server"
 * )
 * @OA\SecurityScheme(
 *     securityScheme="ApiKey",
 *     type="apiKey",
 *     in="header",
 *     name="Authentication"
 * )
 */
