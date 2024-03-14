<?php
require_once __DIR__ . '/InstagramAPI/Instagram.php';
require_once __DIR__ . '/InstagramAPI/Endpoints.php';
require_once __DIR__ . '/InstagramAPI/InstagramQueryId.php';
require_once __DIR__ . '/InstagramAPI/Traits/ArrayLikeTrait.php';
require_once __DIR__ . '/InstagramAPI/Traits/InitializerTrait.php';
require_once __DIR__ . '/InstagramAPI/Model/AbstractModel.php';
require_once __DIR__ . '/InstagramAPI/Model/Account.php';
require_once __DIR__ . '/InstagramAPI/Model/CarouselMedia.php';
require_once __DIR__ . '/InstagramAPI/Model/Comment.php';
require_once __DIR__ . '/InstagramAPI/Model/Location.php';
require_once __DIR__ . '/InstagramAPI/Model/Media.php';
require_once __DIR__ . '/InstagramAPI/Model/Tag.php';
require_once __DIR__ . '/InstagramAPI/Exception/InstagramException.php';
require_once __DIR__ . '/InstagramAPI/Exception/InstagramAuthException.php';
require_once __DIR__ . '/InstagramAPI/Exception/InstagramNotFoundException.php';