import mcsDataProvider from "./providers/data-provider";
import { Admin, Resource } from "react-admin";
import {
	CountriesCreate,
	CountriesEdit,
	CountriesList,
} from "./pages/Countries";
import McsLayout from "./components/McsLayout";
import {
	ProvincesCreate,
	ProvincesEdit,
	ProvincesList,
} from "./pages/Provinces";
import { FC } from "react";
import { CitiesCreate, CitiesEdit, CitiesList } from "./pages/Cities";
import PublicIcon from "@material-ui/icons/Public";
import ExploreIcon from "@material-ui/icons/Explore";
import LocationCityIcon from "@material-ui/icons/LocationCity";
import SettingsIcon from "@material-ui/icons/Settings";
import { OptionsEdit } from "./pages/Options";

const dataProvider = mcsDataProvider("/?rest_route=/mcs/v1");

/*const theme = createMuiTheme({
	overrides: {
		/!*MuiInputBase: {
			input: {
				backgroundColor: "initial !important",
				paddingTop: "23px !important",
				paddingBottom: "6px !important",
			},
		},*!/
	},
});*/

const App: FC = () => (
	/*<Admin dataProvider={dataProvider} layout={McsLayout} theme={theme}>*/
	<Admin dataProvider={dataProvider} layout={McsLayout}>
		<Resource
			name="Countries"
			list={CountriesList}
			create={CountriesCreate}
			edit={CountriesEdit}
			icon={PublicIcon}
		/>
		<Resource name="CountryNames" />
		<Resource
			name="Provinces"
			options={{ label: "State / Province" }}
			list={ProvincesList}
			create={ProvincesCreate}
			edit={ProvincesEdit}
			icon={ExploreIcon}
		/>
		<Resource
			name="Cities"
			list={CitiesList}
			create={CitiesCreate}
			edit={CitiesEdit}
			icon={LocationCityIcon}
		/>
		<Resource name="Options" edit={OptionsEdit} />
	</Admin>
);

export default App;
