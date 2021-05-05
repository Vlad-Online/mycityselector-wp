import React, { useState } from "react";
import Dialog from "@material-ui/core/Dialog";
import DialogContent from "@material-ui/core/DialogContent";
import MuiDialogTitle from "@material-ui/core/DialogTitle";
import {
	Box,
	createMuiTheme,
	IconButton,
	Link,
	Typography,
	withStyles,
} from "@material-ui/core";
import CloseIcon from "@material-ui/icons/Close";
import { ListCountriesProvincesCities } from "./ListCountriesProvincesCities";
import { makeStyles, ThemeProvider } from "@material-ui/core/styles";
import { ListCities } from "./ListCities";

const theme = createMuiTheme({
	typography: {
		htmlFontSize: 10,
	},
});

const styles = (theme) => ({
	root: {
		margin: 0,
		padding: theme.spacing(2),
	},
	closeButton: {
		position: "absolute",
		right: theme.spacing(1),
		top: theme.spacing(1),
		color: theme.palette.grey[500],
	},
});

const DialogTitle = withStyles(styles)(
	({ children, classes, onClose, ...other }) => {
		return (
			<MuiDialogTitle
				disableTypography
				className={classes.root}
				{...other}
			>
				<Box mr={6}>
					<Typography variant="h6">{children}</Typography>
				</Box>

				{onClose ? (
					<IconButton
						aria-label="close"
						className={classes.closeButton}
						onClick={onClose}
					>
						<CloseIcon />
					</IconButton>
				) : null}
			</MuiDialogTitle>
		);
	}
);

const useStyles = makeStyles(() => ({
	root: {
		overflowY: "visible",
	},
	paper: {
		overflowY: "visible",
	},
}));

export const McsWidget = ({ options, data }) => {
	const [open, setOpen] = useState(false);

	const handleLinkClick = (e) => {
		e.preventDefault();
		setOpen(true);
	};

	const handleClose = () => {
		setOpen(false);
	};

	const handleCitySelect = () => {};
	const classes = useStyles();
	return (
		<ThemeProvider theme={theme}>
			<Box m={2}>
				<Link href="#" onClick={handleLinkClick}>
					Choose location
				</Link>
			</Box>
			<Dialog
				open={open}
				onClose={handleClose}
				scroll="paper"
				aria-labelledby="scroll-dialog-title"
				classes={{
					paper: classes.paper,
				}}
				fullWidth
				maxWidth={options?.mode === 0 ? "sm" : "md"}
			>
				<DialogTitle id="scroll-dialog-title" onClose={handleClose}>
					{options?.title ?? ""}
				</DialogTitle>
				<DialogContent className={classes.root}>
					{options?.mode === 0 && (
						<ListCities
							data={data}
							onSelectCity={handleCitySelect}
						/>
					)}
					{options?.mode === 2 && (
						<ListCountriesProvincesCities
							data={data}
							onSelectCity={handleCitySelect}
						/>
					)}
				</DialogContent>
			</Dialog>
		</ThemeProvider>
	);
};
