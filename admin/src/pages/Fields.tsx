import React, { FC } from "react";
import { ListProps } from "@material-ui/core";
import {
	AutocompleteInput,
	BooleanField,
	BooleanInput,
	Create,
	Datagrid,
	Edit,
	EditButton,
	Filter,
	List,
	ReferenceInput,
	SimpleForm,
	TextField,
	TextInput,
} from "react-admin";
import { CreateProps } from "ra-core/lib/controller/details/useCreateController";

/*const FieldsFilter: FC = (props) => (
	<Filter {...props}>
		<TextInput label="Title" source="title" />
		<ReferenceInput
			reference="Countries"
			source="country_id"
			label="Country"
			filterToQuery={(searchText) => ({ title: searchText })}
			resettable
		>
			<AutocompleteInput optionText="title" />
		</ReferenceInput>
		<BooleanInput source="published" label="Published" />
	</Filter>
);*/

export const FieldsList: FC<ListProps> = (props) => {
	return (
		<List {...props} exporter={false}>
			<Datagrid>
				<TextField source="id" label="ID" />
				<TextField source="name" label="Name" />
				<BooleanField source="published" label="Published" />
				<EditButton />
			</Datagrid>
		</List>
	);
};

export const FieldsCreate: FC<CreateProps> = (props) => {
	return (
		<Create {...props}>
			<SimpleForm>
				<TextInput source="name" label="Name" resettable />
				<BooleanInput source="published" label="Published" />
			</SimpleForm>
		</Create>
	);
};

export const FieldsEdit: FC = (props) => {
	return (
		<Edit {...props}>
			<SimpleForm>
				<TextInput source="id" label="ID" disabled />
				<TextInput source="title" label="Title" resettable />
				<ReferenceInput
					label="Country"
					source="country_id"
					reference="Countries"
					filterToQuery={(text) => ({ title: text })}
					resettable
				>
					<AutocompleteInput optionText="title" />
				</ReferenceInput>
				<TextInput source="subdomain" label="SubDomain" resettable />
				<BooleanInput source="published" label="Published" />
				<TextInput source="ordering" label="Ordering" resettable />
			</SimpleForm>
		</Edit>
	);
};
