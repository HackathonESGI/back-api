<?php

declare(strict_types=1);

(new Symfony\Component\Dotenv\Dotenv())->bootEnv(dirname(__DIR__, 4).'/.env.test');
