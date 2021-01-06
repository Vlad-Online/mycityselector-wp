import mcsDataProvider from './providers/data-provider';
import {Admin, Resource} from "react-admin";
import {CountriesCreate, CountriesEdit, CountriesList} from "./pages/Countries";
import McsLayout from "./components/McsLayout";

const dataProvider = mcsDataProvider('/?rest_route=/mcs/v1');

const App = () => (
	<Admin dataProvider={dataProvider}
		   layout={McsLayout}
	>
		<Resource name="countries" list={CountriesList} create={CountriesCreate} edit={CountriesEdit}/>
	</Admin>);

export default App;
