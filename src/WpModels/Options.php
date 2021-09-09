<?php


namespace Mcs\WpModels;

use Mcs\Interfaces\CitiesInterface;
use Mcs\Interfaces\OptionsInterface;

class Options implements OptionsInterface {

	public function getBaseDomain(): string {
		return (string) get_option( 'mcs_base_domain' );
	}

	public function setBaseDomain( string $domain ): bool {
		return update_option( 'mcs_base_domain', $domain );
	}

	public function getDefaultCity(): ?CitiesInterface {
		static $city;
		if ( empty( $city ) ) {
			$city = Cities::findById( (int) get_option( 'mcs_default_city_id' ) );
			if ( empty( $city ) ) {
				$city = Cities::all( 1 )[0] ?? null;
			}
		}

		return $city;
	}

	public function setDefaultCity( CitiesInterface $defaultCity ): bool {
		return update_option( 'mcs_default_city_id', $defaultCity->getId() );
	}

	public function getSeoMode(): int {
		return (int) get_option( 'mcs_seo_mode' );
	}

	public function setSeoMode( int $seoMode = self::SEO_MODE_COOKIE ): bool {
		return update_option( 'mcs_seo_mode', $seoMode );
	}

	public function getCountryChooseEnabled(): bool {
		return (bool) get_option( 'mcs_country_choose_enabled' );
	}

	public function setCountryChooseEnabled( bool $countryChooseEnabled = false ): bool {
		return update_option( 'mcs_country_choose_enabled', $countryChooseEnabled );
	}

	public function getProvinceChooseEnabled(): bool {
		return (bool) get_option( 'mcs_province_choose_enabled' );
	}

	public function setProvinceChooseEnabled( bool $provinceChooseEnabled ): bool {
		return update_option( 'mcs_province_choose_enabled', $provinceChooseEnabled );
	}

	public function getAskMode(): int {
		return (int) get_option( 'mcs_ask_mode' );
	}

	public function setAskMode( int $askMode = self::ASK_MODE_DIALOG ): bool {
		return update_option( 'mcs_ask_mode', $askMode );
	}

	public function getRedirectNextVisits(): bool {
		return (bool) get_option( 'mcs_redirect_next_visits' );
	}

	public function setRedirectNextVisits( bool $redirectNextVisits = false ): bool {
		return update_option( 'mcs_redirect_next_visits', $redirectNextVisits );
	}

	public function getLogEnabled(): bool {
		return (bool) get_option( 'mcs_log_enabled' );
	}

	public function setLogEnabled( bool $logEnabled = false ): bool {
		return update_option( 'mcs_log_enabled', $logEnabled );
	}

	public function getDebugEnabled(): bool {
		return (bool) get_option( 'mcs_debug_enabled' );
	}

	public function setDebugEnabled( bool $debugEnabled = false ): bool {
		return update_option( 'mcs_debug_enabled', $debugEnabled );
	}

	public function toArray(): array {
		$defaultCity = $this->getDefaultCity();

		return [
			'id'                      => 0,
			'base_domain'             => $this->getBaseDomain(),
			'default_city_id'         => $defaultCity ? $defaultCity->getId() : null,
			'seo_mode'                => $this->getSeoMode(),
			'country_choose_enabled'  => $this->getCountryChooseEnabled(),
			'province_choose_enabled' => $this->getProvinceChooseEnabled(),
			'ask_mode'                => $this->getAskMode(),
			'redirect_next_visits'    => $this->getRedirectNextVisits(),
			'log_enabled'             => $this->getLogEnabled(),
			'debug_enabled'           => $this->getDebugEnabled()
		];
	}

	public static function getInstance(): OptionsInterface {
		static $options;
		if ( ! $options ) {
			$options = new self();
		}

		return $options;
	}
}
