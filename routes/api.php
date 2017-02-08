<?php

Route::get('/{indicador}', 'IndicadorController@api')
	->where('indicador', '[A-Za-z]+');
