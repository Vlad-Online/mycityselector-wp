import React, { FC, useCallback } from "react";
import {
	List,
	Datagrid,
	TextField,
	Create,
	SimpleForm,
	TextInput,
	EditButton,
	Edit,
	ReferenceField,
	ReferenceInput,
	AutocompleteInput,
	BooleanInput,
	BooleanField,
} from "react-admin";
import { useFormState } from "react-final-form";
import { ListProps } from "@material-ui/core";
import { CreateProps } from "ra-core/lib/controller/details/useCreateController";

export const CitiesList: FC<ListProps> = (props) => {
	return (
		<List {...props} exporter={false}>
			<Datagrid>
				<TextField source="id" label="ID" />
				<ReferenceField source="country_id" reference="Countries">
					<TextField source="title" label="Country" />
				</ReferenceField>
				<ReferenceField source="province_id" reference="Provinces">
					<TextField source="title" label="State / Province" />
				</ReferenceField>
				<TextField source="title" label="Title" />
				<TextField source="subdomain" label="Subdomain" />
				<BooleanField source="published" label="Published" />
				<TextField source="ordering" label="Ordering" />
				<EditButton />
			</Datagrid>
		</List>
	);
};

const ProvinceReferenceInput: FC = (props) => {
	const { values } = useFormState();
	const filterToQuery = useCallback(
		(text) => ({
			title: text,
		}),
		[values.country_id]
	);
	return (
		<ReferenceInput
			{...props}
			label="State / Province"
			source="province_id"
			reference="Provinces"
			filter={{ country_id: values.country_id }}
			filterToQuery={filterToQuery}
			resettable
		>
			<AutocompleteInput optionText="title" />
		</ReferenceInput>
	);
};

export const CitiesCreate: FC<CreateProps> = (props) => {
	return (
		<Create {...props}>
			<SimpleForm>
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
				<ProvinceReferenceInput />
				<TextInput source="subdomain" label="SubDomain" resettable />
				<BooleanInput source="published" label="Published" />
				<TextInput source="ordering" label="Ordering" resettable />
			</SimpleForm>
		</Create>
	);
};

export const CitiesEdit: FC = (props) => {
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
				<ProvinceReferenceInput />
				<TextInput source="subdomain" label="SubDomain" resettable />
				<BooleanInput source="published" label="Published" />
				<TextInput source="ordering" label="Ordering" resettable />
			</SimpleForm>
		</Edit>
	);
};
