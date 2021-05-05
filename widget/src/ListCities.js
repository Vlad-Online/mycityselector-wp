import React, { useState, useMemo, useCallback } from "react";
import _ from "lodash";
import McsList from "./McsList";

export const ListCities = ({ data, onSelectCity }) => {
	const [selectedCityIndex, setSelectedCityIndex] = useState(null);
	const cities = useMemo(
		() => _.sortBy(_.get(data, "cities", {}), ["title"]),
		[data]
	);

	const handleCityClick = useCallback(
		(index) => {
			setSelectedCityIndex(index);
			onSelectCity();
		},
		[onSelectCity]
	);

	return (
		<McsList
			withCitySearch
			handleItemClick={handleCityClick}
			items={cities}
			selectedIndex={selectedCityIndex}
			onSearchInput={() => setSelectedCityIndex(null)}
		/>
	);
};
