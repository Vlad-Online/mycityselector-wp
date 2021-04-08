<?php

namespace Mcs\Interfaces;

interface OptionsInterface {
	const SEO_MODE_DISABLED = 0;
	const SEO_MODE_SUBDOMAIN = 1;
	const SEO_MODE_SUBFOLDER = 2;

	const ASK_MODE_DIALOG = 0;
	const ASK_MODE_TOOLTIP = 1;
	const ASK_MODE_REDIRECT = 2;

	public function getBaseDomain(): string;

	public function setBaseDomain( string $domain ): bool;

	public function getDefaultCityId(): int;

	/**
	 * @param int|null $defaultCityId
	 *
	 * @return bool
	 */
	public function setDefaultCityId(  $defaultCityId ): bool;

	public function getSeoMode(): int;

	public function setSeoMode( int $seoMode = self::SEO_MODE_DISABLED ): bool;

	public function getCountryChooseEnabled(): bool;

	public function setCountryChooseEnabled( bool $countryChooseEnabled = false ): bool;

	public function getProvinceChooseEnabled(): bool;

	public function setProvinceChooseEnabled( bool $provinceChooseEnabled ): bool;

	public function getAskMode(): int;

	public function setAskMode( int $askMode = self::ASK_MODE_DIALOG ): bool;

	public function getRedirectNextVisits(): bool;

	public function setRedirectNextVisits( bool $redirectNextVisits ): bool;

	public function getLogEnabled(): bool;

	public function setLogEnabled( bool $logEnabled = false ): bool;

	public function getDebugEnabled(): bool;

	public function setDebugEnabled( bool $debugEnabled = false ): bool;

	public function toArray(): array;

}
