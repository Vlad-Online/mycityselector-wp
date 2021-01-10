import mcsDataProvider from './providers/data-provider';
import {Admin, Resource} from "react-admin";
import {CountriesCreate, CountriesEdit, CountriesList} from "./pages/Countries";
import McsLayout from "./components/McsLayout";

const dataProvider = mcsDataProvider('/?rest_route=/mcs/v1');

const App = () => (
	<Admin dataProvider={dataProvider}
		   layout={McsLayout}
	>
		<Resource name="Countries" list={CountriesList} create={CountriesCreate} edit={CountriesEdit}/>
		<Resource name="CountryNames"/>
		<Resource name="Provinces" list={CountriesList} create={CountriesCreate} edit={CountriesEdit}/>
		{/*<Resource name="Cities" list={CountriesList} create={CountriesCreate} edit={CountriesEdit}/>*/}
	</Admin>);

export default App;
