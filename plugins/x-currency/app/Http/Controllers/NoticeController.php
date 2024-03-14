<?php

namespace XCurrency\App\Http\Controllers;

use XCurrency\App\Http\Controllers\Controller;
use XCurrency\WpMVC\Routing\Response;

class NoticeController extends Controller {
    public function maybe_latter() {
        set_transient( 'x-currency-fb-g-notice', true, WEEK_IN_SECONDS );
        return Response::send( [] );
    }
}