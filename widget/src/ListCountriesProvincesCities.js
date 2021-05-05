import React, { useState } from "react";
import _ from "lodash";
import McsList from "./McsList";
import { Grid } from "@material-ui/core";

export const ListCountriesProvincesCities = ({ data, onSelectCity }) => {
	const [selectedCountryIndex, setSelectedCountryIndex] = useState(null);
	const [selectedProvinceIndex, setSelectedProvinceIndex] = useState(null);
	const [selectedCityIndex, setSelectedCityIndex] = useState(null);
	const countries = _.sortBy(_.get(data, "countries", {}), ["title"]);
	const selectedCountryId = _.get(countries[selectedCountryIndex], "id");

	const provinces = _.sortBy(
		_.filter(_.get(data, "provinces", {}), [
			"country_id",
			selectedCountryId,
		]),
		["title"]
	);
	const selectedProvinceId = _.get(provinces[selectedProvinceIndex], "id");
	const cities = _.sortBy(
		_.filter(_.get(data, "cities", {}), (city) => {
			if (selectedProvinceId) {
				return _.get(city, "province_id") === selectedProvinceId;
			} else if (selectedCountryId) {
				return _.get(city, "country_id") === selectedCountryId;
			}
			return false;
		}),
		["title"]
	);

	const handleCountryClick = (index) => {
		if (selectedCountryIndex !== index) {
			setSelectedCountryIndex(index);
			setSelectedProvinceIndex(null);
			setSelectedCityIndex(null);
		}
	};
	const handleProvinceClick = (index) => {
		if (selectedProvinceIndex !== index) {
			setSelectedProvinceIndex(index);
			setSelectedCityIndex(null);
		}
	};
	const handleCityClick = (index) => {
		setSelectedCityIndex(index);
		onSelectCity();
	};

	return (
		<Grid container>
			<Grid item xs={12} sm={4}>
				<McsList
					title="Country"
					handleItemClick={handleCountryClick}
					items={countries}
					selectedIndex={selectedCountryIndex}
				/>
			</Grid>
			<Grid item xs={12} sm={4}>
				<McsList
					title="State / Province"
					handleItemClick={handleProvinceClick}
					items={provinces}
					selectedIndex={selectedProvinceIndex}
				/>
			</Grid>
			<Grid item xs={12} sm={4}>
				<McsList
					title="City"
					handleItemClick={handleCityClick}
					items={cities}
					selectedIndex={selectedCityIndex}
				/>
			</Grid>
		</Grid>
	);
};
