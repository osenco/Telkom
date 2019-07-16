<?php

/**
 * These helper functions wrap around the various telkom API classes for more concise code
 */

if (!function_exists('telkom_setup_telkom')) {
	function telkom_setup_config(array $config = [], $api = 'STK')
	{
    	$API = "Osen\\Telkom\\{$api}";
		return $API::init($config);
	}
}

if (!function_exists('telkom_setup_stk')) {
	function telkom_setup_stk(array $config = [])
	{
		return Osen\Telkom\STK::init($config);
	}
}

if (!function_exists('telkom_setup_c2b')) {
	function telkom_setup_c2b(array $config = [])
	{
		return Osen\Telkom\C2B::init($config);
	}
}

if (!function_exists('telkom_setup_b2c')) {
	function telkom_setup_b2c(array $config = [])
	{
		return Osen\Telkom\B2C::init($config);
	}
}

if (!function_exists('telkom_setup_b2b')) {
	function telkom_setup_b2b(array $config = [])
	{
		return Osen\Telkom\B2B::init($config);
	}
}

if (!function_exists('telkom_stk_push')) {
	function telkom_stk_push(string $phone, int $amount, string $reference)
	{
		return Osen\Telkom\STK::send($phone, $amount, $reference);
	}
}

if (!function_exists('telkom_c2b_request')) {
	function telkom_c2b_request(string $phone, int $amount, string $reference)
	{
		return Osen\Telkom\C2B::send($phone, $amount, $reference);
	}
}

if (!function_exists('telkom_b2c_request')) {
	function telkom_b2c_request(string $phone, int $amount, string $reference)
	{
		return Osen\Telkom\B2C::send($phone, $amount, $reference);
	}
}

if (!function_exists('telkom_b2b_request')) {
	function telkom_b2b_request(string $phone, int $amount, string $reference)
	{
		return Osen\Telkom\B2B::send($phone, $amount, $reference);
	}
}

if (!function_exists('telkom_validate')) {
	function telkom_validate(callable $callback = null)
	{
		return Osen\Telkom\Service::validate($callback);
	}
}

if (!function_exists('telkom_confirm')) {
	function telkom_confirm(callable $callback = null)
	{
		return Osen\Telkom\Service::confirm($callback);
	}
}

if (!function_exists('telkom_reconcile')) {
	function telkom_reconcile(callable $callback = null)
	{
		return Osen\Telkom\Service::reconcile($callback);
	}
}

if (!function_exists('telkom_results')) {
	function telkom_results(callable $callback = null)
	{
		return Osen\Telkom\Service::results($callback);
	}
}

if (!function_exists('telkom_timeout')) {
	function telkom_timeout(callable $callback = null)
	{
		return Osen\Telkom\Service::timeout();
	}
}
