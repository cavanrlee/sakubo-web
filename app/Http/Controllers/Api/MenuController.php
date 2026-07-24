<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BotNavMenu;
use App\Models\Menu;
use Illuminate\Http\Request;

class MenuController extends Controller
{
	public function menu(Request $request)
	{
		return response()->json([
			'menu_items' => Menu::get(),
		]);
	}

	public function botNavMenu(Request $request)
	{
		return response()->json([
			'bot_nav_items' => BotNavMenu::get(),
		]);
	}
}