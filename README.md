# 🌱 Elastic Beanstalk starter for Cockpit CMS

Deploying [Cockpit CMS](https://getcockpit.com/) on [AWS Elastic Beanstalk](https://aws.amazon.com/elasticbeanstalk/) is a real pain in the backside, so I made a quick starter with Docker.

## 🚀 Quickstart

1. Clone the repo
	```git
	git clone https://github.com/lewisdonovan/eb-cockpit-starter.git
	```

2. Open the `.env` file and change the values for `COCKPIT_APP_NAME` and `COCKPIT_SEC_KEY`.

3. Initialise Elastic Beanstalk
	```bash
	eb init
	```

4. Deploy
	```bash
	eb deploy
	```

## ⚙️ Custom config

The repo provides a `.env` file with variables for all the settings in [Cockpit config](https://getcockpit.com/documentation/core/quickstart/configuration). You can uncomment and edit them to suit your needs.

The whole of `cockpit-core` is in the `app` folder, **do not edit it**, you can make edits in the `custom` folder instead. Anything you add to the `custom` folder will overwrite the corresponding Cockpit files when the EB container builds and the rest of the Cockpit files will remain untouched.

For example, if you create a file at `custom/modules/App/views/auth/login.php`, it will replace the file at `/app/modules/App/views/auth/login.php` in the container build.

This way you can safely make edits without overwriting the Cockpit source.

## Upkeep
This is a working solution, but it's not actively maintained. Issues and PRs will be responded to as and when I can.

## Contribute
Feel free to [submit a PR](https://github.com/lewisdonovan/eb-cockpit-starter/pulls) if you've fixed an open issue. Thank you.