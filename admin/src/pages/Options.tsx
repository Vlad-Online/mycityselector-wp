import React, { FC } from "react";
import {
	AutocompleteInput,
	BooleanInput,
	Edit,
	ReferenceInput,
	SimpleForm,
	TextInput,
	SelectInput,
	useNotify,
	useRefresh,
	Toolbar,
	SaveButton,
} from "react-admin";
import { Grid } from "@material-ui/core";

const OptionsToolbar = (props: any) => (
	<Toolbar {...props}>
		<SaveButton />
	</Toolbar>
);

const GridSimpleForm = (props: any) => (
	<Grid xs={12} md={10} lg={6}>
		<SimpleForm {...props} toolbar={<OptionsToolbar />}>
			<TextInput
				source="base_domain"
				label="Base domain (example: wordpress.org)"
				resettable
				fullWidth
			/>
			<ReferenceInput
				label="Default City"
				source="default_city_id"
				reference="Cities"
				filterToQuery={(text) => ({
					title: text,
					published: 1,
				})}
				resettable
				fullWidth
			>
				<AutocompleteInput
					optionText="title"
					helperText="Only published city can be selected"
				/>
			</ReferenceInput>
			<SelectInput
				source="seo_mode"
				label="SEO mode"
				choices={[
					{ id: "0", name: "Disabled (example: wordpress.org)" },
					{
						id: "1",
						name:
							"Subdomain mode (example: new-york.wordpress.org)",
					},
					{
						id: "2",
						name:
							"Subfolder mode (example: wordpress.org/new-york)",
					},
				]}
				fullWidth
			/>
			<BooleanInput
				source="country_choose_enabled"
				label="Country choose enabled"
				fullWidth
				helperText="Allow user select Country as location"
			/>
			<BooleanInput
				source="province_choose_enabled"
				label="Province / State choose enabled"
				fullWidth
				helperText="Allow user select Province / State as location"
			/>
			<SelectInput
				source="ask_mode"
				label="Ask mode"
				choices={[
					{ id: "0", name: "Show dialog with list of locations" },
					{ id: "1", name: "Just show tooltip" },
					{
						id: "2",
						name: "Don't ask, force redirect to detected location",
					},
				]}
				fullWidth
			/>
			<BooleanInput
				source="redirect_next_visits"
				label="Auto redirect user on previously selected location"
				fullWidth
				helperText="If selected, user will be redirected to selected location on next visits"
			/>
			<BooleanInput
				source="log_enabled"
				label="Enable logging"
				fullWidth
				helperText="Enable plugin logging"
			/>
			<BooleanInput
				source="debug_enabled"
				label="Enable debug"
				fullWidth
				helperText="Enable plugin debug mode"
			/>
		</SimpleForm>
	</Grid>
);

export const OptionsEdit: FC = (props) => {
	const notify = useNotify();
	const refresh = useRefresh();
	const onSuccess = () => {
		notify(`Options saved`);
		refresh();
	};
	const onFailure = (error: any) => {
		notify(`Could not save options: ${error.message}`, "error");
	};
	return (
		<Edit
			{...props}
			onSuccess={onSuccess}
			onFailure={onFailure}
			mutationMode="pessimistic"
			title="Edit plugin options"
		>
			<GridSimpleForm />
		</Edit>
	);
};
