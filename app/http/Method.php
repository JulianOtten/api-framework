<?php

namespace App\Http;

enum Method: string
{
    case Post = "Post";
    case Get = "Get";
    case Put = "Put";
    case Patch = "Patch";
    case Delete = "Delete";
    case Options = "Options";
    case Any = "Any";
}
