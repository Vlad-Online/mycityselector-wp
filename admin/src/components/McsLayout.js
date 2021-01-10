import {Layout} from 'react-admin';
import {makeStyles} from "@material-ui/core";

const useStyles = makeStyles(theme => ({
	/*	root: {
			display: 'flex',
			flexDirection: 'column',
			zIndex: 1,
			// minHeight: '100vh',
			backgroundColor: theme.palette.background.default,
			position: 'relative',
		}*/
	root: {
		minHeight: 0
		/*color: '#000000de',
		width: '100%',
		display: 'flex',
		zIndex: 1,
		position: 'relative',
		minWidth: 'fit-content',
		flexDirection: 'column',
		backgroundColor: '#fafafa',*/
	},
	appFrame : {
		marginTop: 0
	}
}));

const EmptyAppBar = () => null

const McsLayout = props => {
	const classes = useStyles();
	return (<Layout
		{...props}
		classes={classes}
		appBar={EmptyAppBar}
	/>)
};

export default McsLayout;
