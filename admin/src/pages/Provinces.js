import React from 'react';
import {
	List, Datagrid, TextField, Create, SimpleForm,
	TextInput, EditButton,
	Edit, ReferenceField, ReferenceInput, AutocompleteInput,
	BooleanInput
} from 'react-admin';
import {BooleanWrapper} from "../components/Buttons";

export const ProvincesList = (props) => {
	return (
		<List {...props}>
			<Datagrid rowClick="edit">
				<TextField source="id" label="ID"/>
				<ReferenceField source="country_id" reference="Countries">
					<TextField source="title" label="Country"/>
				</ReferenceField>
				<TextField source="title" label="Title"/>
				<TextField source="subdomain" label="Subdomain"/>
				<BooleanWrapper source="published" label="Published"/>
				<TextField source="ordering" label="Ordering"/>
				<EditButton/>
			</Datagrid>
		</List>
	)
}

export const ProvincesCreate = (props) => (
	<Create {...props}>
		<SimpleForm>
			<TextInput source="title" label="Title"/>
			<ReferenceInput label="Country" source="country_id" reference="Countries" filterToQuery={text => ({title: text})}>
				<AutocompleteInput optionText="title"/>
			</ReferenceInput>
			<TextInput source="subdomain" label="SubDomain"/>
			<BooleanInput source="published" label="Published"/>
			<TextInput source="ordering" label="Ordering"/>
		</SimpleForm>
	</Create>)

export const ProvincesEdit = (props) => (
	<Edit {...props}>
		<SimpleForm>
			<TextInput source="id" label="ID" disabled/>
			<TextInput source="title" label="Title"/>
			<ReferenceInput label="Country" source="country_id" reference="Countries" filterToQuery={text => ({title: text})}>
				<AutocompleteInput optionText="title"/>
			</ReferenceInput>
			<TextInput source="subdomain" label="SubDomain"/>
			<BooleanInput source="published" label="Published"/>
			<TextInput source="ordering" label="Ordering"/>
		</SimpleForm>
	</Edit>)
