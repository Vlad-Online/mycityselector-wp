import React from 'react';
import {
	List, Datagrid, TextField, Create, SimpleForm,
	TextInput, BooleanInput, EditButton,
	Edit
} from 'react-admin';
import {BooleanWrapper} from "../components/Buttons";

export const CountriesList = (props) => {
	return (
		<List {...props}>
			<Datagrid rowClick="edit">
				<TextField source="id" label="ID"/>
				<TextField source="title" label="Title"/>
				<TextField source="subdomain" label="Subdomain"/>
				<BooleanWrapper source="published" label="Published"/>
				<TextField source="ordering" label="Ordering"/>
				<TextField source="code" label="Country Code"/>
				<TextField source="domain" label="Domain"/>
				<EditButton/>
			</Datagrid>
		</List>
	)
}

export const CountriesCreate = (props) => (
	<Create {...props}>
		<SimpleForm>
			<TextInput source="title" label="Title"/>
			<TextInput source="subdomain" label="SubDomain"/>
			<BooleanInput source="published" label="Published"/>
			<TextInput source="ordering" label="Ordering"/>
			<TextInput source="code" label="Country code"/>
			<TextInput source="domain" label="Domain"/>
		</SimpleForm>
	</Create>)

export const CountriesEdit = (props) => (
	<Edit {...props}>
		<SimpleForm>
			<TextInput source="id" label="ID"/>
			<TextInput source="title" label="Title"/>
			<TextInput source="subdomain" label="SubDomain"/>
			<BooleanInput source="published" label="Published"/>
			<TextInput source="ordering" label="Ordering"/>
			<TextInput source="code" label="Country code"/>
			<TextInput source="domain" label="Domain"/>
		</SimpleForm>
	</Edit>)
