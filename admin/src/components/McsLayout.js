import { Layout } from 'react-admin';

const EmptyAppBar = () => null

const McsLayout = props => <Layout
	{...props}
	appBar={EmptyAppBar}
/>;

export default McsLayout;
