import React, { FC } from "react";
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
	ReferenceManyField,
	SingleFieldList,
	ChipField,
	Filter,
} from "react-admin";
import { ListProps } from "@material-ui/core";
import { CreateProps } from "ra-core/lib/controller/details/useCreateController";

const FieldValuesFilter: FC = (props) => (
	<Filter {...props}>
		<ReferenceInput
			reference="Fields"
			source="field_id"
			label="Field name"
			filterToQuery={(searchText) => ({ name: searchText })}
			resettable
		>
			<AutocompleteInput optionText="name" />
		</ReferenceInput>
		<BooleanInput source="default" label="Default" />
	</Filter>
);

export const FieldValuesList: FC<ListProps> = (props) => {
	return (
		<List
			{...props}
			exporter={false}
			title="Field Values"
			filters={<FieldValuesFilter />}
		>
			<Datagrid>
				<TextField source="id" label="ID" />
				<ReferenceField source="field_id" reference="Fields">
					<TextField source="name" label="Field" />
				</ReferenceField>
				<ReferenceManyField
					reference="CountryFieldValues"
					target="field_value_id"
					label="Countries"
				>
					<SingleFieldList>
						<ReferenceField
							reference="Countries"
							source="country_id"
						>
							<ChipField source="subdomain" />
						</ReferenceField>
					</SingleFieldList>
				</ReferenceManyField>
				<ReferenceManyField
					reference="ProvinceFieldValues"
					target="field_value_id"
					label="States / Provinces"
				>
					<SingleFieldList>
						<ReferenceField
							reference="Provinces"
							source="province_id"
						>
							<ChipField source="subdomain" />
						</ReferenceField>
					</SingleFieldList>
				</ReferenceManyField>
				<ReferenceManyField
					reference="CityFieldValues"
					target="field_value_id"
					label="Cities"
				>
					<SingleFieldList>
						<ReferenceField reference="Cities" source="city_id">
							<ChipField source="subdomain" />
						</ReferenceField>
					</SingleFieldList>
				</ReferenceManyField>
				<TextField source="value" label="Value" />
				<BooleanField source="default" label="Default" />
				<EditButton />
			</Datagrid>
		</List>
	);
};

export const FieldValuesCreate: FC<CreateProps> = (props) => {
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
				<TextInput source="subdomain" label="SubDomain" resettable />
				<BooleanInput source="published" label="Published" />
				<TextInput source="ordering" label="Ordering" resettable />
			</SimpleForm>
		</Create>
	);
};

export const FieldValuesEdit: FC = (props) => {
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
