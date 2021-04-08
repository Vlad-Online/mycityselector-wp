import {
	Layout,
	AppBar,
	CoreLayoutProps,
	MenuItemLink,
	getResources,
} from "react-admin";
import { makeStyles, useMediaQuery } from "@material-ui/core";
import { ComponentType } from "react";
import { useSelector } from "react-redux";
import DefaultIcon from "@material-ui/icons/ViewList";
import { MenuProps } from "ra-ui-materialui/src/layout/Menu";
import SettingsIcon from "@material-ui/icons/Settings";

const useStyles = makeStyles(() => ({
	root: {
		minHeight: 0,
	},
	appFrame: {
		marginTop: 0,
	},
}));

const Menu: ComponentType<MenuProps> = ({ onMenuClick, logout }) => {
	const isXSmall = useMediaQuery((theme: any) =>
		theme.breakpoints.down("xs")
	);
	const open = useSelector((state) => state.admin.ui.sidebarOpen);
	const resources = useSelector(getResources);
	return (
		<>
			{resources.map((resource) => {
				return resource.hasList ? (
					<MenuItemLink
						key={resource.name}
						to={`/${resource.name}`}
						primaryText={
							(resource.options && resource.options.label) ||
							resource.name
						}
						leftIcon={
							resource.icon ? <resource.icon /> : <DefaultIcon />
						}
						onClick={onMenuClick}
						sidebarIsOpen={open}
					/>
				) : null;
			})}
			<MenuItemLink
				to="/Options/0"
				primaryText="Options"
				leftIcon={<SettingsIcon />}
				onClick={onMenuClick}
				sidebarIsOpen={open}
			/>
			{isXSmall && logout}
		</>
	);
};
const McsLayout: ComponentType<CoreLayoutProps> = (props) => {
	const classes = useStyles();
	return (
		<Layout
			{...props}
			classes={classes}
			appBar={() => <AppBar position="relative" />}
			menu={Menu}
		/>
	);
};

export default McsLayout;
