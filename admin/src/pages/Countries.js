import React, {Fragment} from 'react';
import {
	List, Datagrid, TextField, Create, SimpleForm,
	TextInput, BooleanInput, EditButton,
	Edit, Filter,
	BulkDeleteButton,
	BooleanField
} from 'react-admin';
import {PublishButton, UnPublishButton} from "../components/Buttons";

const CountriesFilter = (props) => (
	<Filter {...props}>
		<TextInput label="Title" source="title"/>
		<BooleanInput source="published" label="Published"/>
	</Filter>
)

const CountriesBulkActionButtons = props => (
	<Fragment>
		<PublishButton {...props} />
		<UnPublishButton {...props} />
		<BulkDeleteButton {...props} />
	</Fragment>
);


export const CountriesList = (props) => {
	return (
		<List {...props}
			  filters={<CountriesFilter/>}
			  bulkActionButtons={<CountriesBulkActionButtons/>}
		>
			<Datagrid rowClick="edit">
				<TextField source="id" label="ID"/>
				<TextField source="title" label="Title"/>
				<TextField source="subdomain" label="Subdomain"/>
				<BooleanField source="published" label="Published"/>
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
