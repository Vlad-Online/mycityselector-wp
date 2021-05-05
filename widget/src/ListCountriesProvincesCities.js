import React from "react";
import { makeStyles } from "@material-ui/core/styles";
import List from "@material-ui/core/List";
import ListItem from "@material-ui/core/ListItem";
import ListItemIcon from "@material-ui/core/ListItemIcon";
import ListItemText from "@material-ui/core/ListItemText";
import Divider from "@material-ui/core/Divider";
import InboxIcon from "@material-ui/icons/Inbox";
import DraftsIcon from "@material-ui/icons/Drafts";

export const ListCountriesProvincesCities = ({ data }) => {
	return (
		<List component="nav">
			<ListItem
				button
				selected={selectedIndex === 2}
				onClick={(event) => handleListItemClick(event, 2)}
			>
				<ListItemText primary="Trash" />
			</ListItem>
			<ListItem
				button
				selected={selectedIndex === 3}
				onClick={(event) => handleListItemClick(event, 3)}
			>
				<ListItemText primary="Spam" />
			</ListItem>
		</List>
	);
};
