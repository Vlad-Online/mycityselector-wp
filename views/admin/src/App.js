//import logo from './logo.svg';
//import './App.css';
import simpleRestProvider from 'ra-data-simple-rest';
import {Admin} from "react-admin";

function App() {
	return (
		<Admin dataProvider={simpleRestProvider('/?rest_route=/mcs/v1/')}>

		</Admin>
	);
}

export default App;
