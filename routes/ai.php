<?php

use App\Mcp\Servers\LibraryServer;
use Laravel\Mcp\Facades\Mcp;

Mcp::web('/mcp', LibraryServer::class);
