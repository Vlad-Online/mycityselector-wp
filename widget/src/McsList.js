import ListItem from "@material-ui/core/ListItem";
import ListItemText from "@material-ui/core/ListItemText";
import { Box, Typography } from "@material-ui/core";
import React, { useState, memo, useMemo, useCallback } from "react";
import List from "@material-ui/core/List";
import _ from "lodash";
import { makeStyles } from "@material-ui/core/styles";

const McsListItem = memo(({ isSelected, onClick, index, title }) => {
	return (
		<Box borderLeft={isSelected ? 4 : 0} borderColor="primary.main">
			<ListItem
				button
				selected={isSelected}
				onClick={() => onClick(index)}
			>
				<ListItemText primary={title} />
			</ListItem>
		</Box>
	);
});

const useStyles = makeStyles(() => ({
	root: {
		overflow: "auto",
		maxHeight: "calc(100vh - 200px)",
		"&::-webkit-scrollbar": {
			width: "0.5rem",
		},
		"&::-webkit-scrollbar-track": {
			boxShadow: "inset 0 0 6px rgba(0,0,0,0.00)",
			webkitBoxShadow: "inset 0 0 6px rgba(0,0,0,0.00)",
		},
		"&::-webkit-scrollbar-thumb": {
			backgroundColor: "rgba(0,0,0,.1)",
		},
	},
}));

const McsList = ({
	title,
	items,
	selectedIndex,
	handleItemClick,
	withCitySearch,
	onSearchInput,
}) => {
	const [searchTitle, setSearchTitle] = useState("");
	const classes = useStyles();
	const filteredItems = useMemo(() => {
		if (searchTitle) {
			const loweredSearchTitle = _.toLower(searchTitle);
			return _.filter(items, (item) => {
				return _.includes(
					_.toLower(_.get(item, "title")),
					loweredSearchTitle
				);
			});
		}
		return items;
	}, [items, searchTitle]);

	const handleSearchInput = useCallback(
		(e) => {
			setSearchTitle(e.target.value);
			onSearchInput();
		},
		[onSearchInput]
	);

	return (
		<>
			{!!title && <Typography variant="h6">{title}</Typography>}
			{withCitySearch && (
				<input
					type="search"
					className="search-field"
					placeholder="Search…"
					value={searchTitle}
					onChange={handleSearchInput}
				/>
			)}
			<List component="nav" className={classes.root}>
				{filteredItems.map((item, index) => {
					const isSelected = selectedIndex === index;
					return (
						<McsListItem
							key={_.get(item, "id")}
							index={index}
							onClick={handleItemClick}
							isSelected={isSelected}
							title={_.get(item, "title")}
						/>
					);
				})}
			</List>
		</>
	);
};

export default McsList;
