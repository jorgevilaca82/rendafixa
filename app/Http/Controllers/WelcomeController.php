<?php namespace App\Http\Controllers;

use App\Business\IndicadorBusiness;
use App\Business\PreferencesVO;
use Illuminate\Support\Facades\Cookie;

class WelcomeController extends Controller
{

	public function index()
	{
		$indicadorCDI = new IndicadorBusiness(IndicadorBusiness::$CDI);
		$cdi = $indicadorCDI->getUltimoIndiceXML()->getValue();

		$indicadorPoupanca = new IndicadorBusiness(IndicadorBusiness::$POUPANCA);
		$poupanca = $indicadorPoupanca->getUltimoIndiceXML()->getValue();

		$indicadorSELIC = new IndicadorBusiness(IndicadorBusiness::$SELIC);
		$selic = $indicadorSELIC->getUltimoIndiceXML()->getValue();

		$amount = Cookie::get(PreferencesVO::PREFERENCES_AMOUNT, PreferencesVO::DEFAULT_AMOUNT);
		$period = Cookie::get(PreferencesVO::PREFERENCES_PERIOD, PreferencesVO::DEFAULT_PERIOD);
		$taxcdb = Cookie::get(PreferencesVO::PREFERENCES_TAXCDB, PreferencesVO::DEFAULT_TAXCDB);
		$taxlci = Cookie::get(PreferencesVO::DEFAULT_TAXLCI, PreferencesVO::DEFAULT_TAXLCI);

		$viewData = compact('cdi', 'poupanca', 'selic', 'amount', 'period', 'taxcdb', 'taxlci');
		// dd($viewData);
		return view('welcome', $viewData);
	}


}
