# <img alt="Airplane" src="images/wpforge-icon-orig-c1-sm.jpg" style="vertical-align: middle;"/> `wpforge`

## Motivation

As webmaster of a WordPress website for a non-profit organization qualifying for a "free"
hosting account that does not provide or support its own "Staging" environment, I find it
stressful when making changes directly to the site, since any of them could potentially
cause an outage.
 
Also, not having prior WordPress expertise, I would like to be able to experiment with
new features and "practice" recommended maintenance procedures prior to their introduction
into the production system, thereby increasing my confidence in these proposed changes and
reducing the risk of an outage when they are applied.

## Approach

Since I am familiar with software development workflows involving a local replica of a
production system using Docker containers, I thought such a workflow might be similarly
valuable to my role as webmaster as described above.

## Goal

Establish a local dockerized "clone" of the organization's WordPress website which already
uses the [Duplicator](https://wordpress.org/plugins/duplicator/) plugin as its backup
strategy as part of a change workflow to mitigate risk and facilitate experimentation.
Proposed changes to the website can then be applied to the local clone within
the expendable Docker container for "practice runs" or "what-if" experiments
without incurring any risk to the production website.

## Workflow

Using the steps below, a Docker container is created (or updated) to run a copy of the
production website, and in which proposed changes can subsequently be made in a completely
separate environment, without incurring risk to the original production website.

See [this tutorial](https://duplicator.com/knowledge-base/classic-install/) for additional
context relevant to the use of "Duplicator" in creating a copy of a WordPress website.

1. From within the production website's `Duplicator` plugin administrator page, create and
   download a "Duplicator Package" containing a complete export of the website.
   - E.g., download & store the package files within a `backups` sub-folder
2. Copy the files comprising the "Duplicator Package" into an _empty_ `docker/volumes/wordpress`
   folder; e.g.:
   - `$ mkdir docker/volumes/wordpress || rm -r docker/volumes/wordpress/*`
   - `$ (cd backups/230806-duplicator; cp installer.php *_archive.zip ../../docker/volumes/wordpress)`
3. Bring up the local "clone" website by starting the Docker containers; e.g.
   - `$ (cd docker; docker-compose up)`
4. After giving enough time for the "Duplicator installer" application to initialize,
   navigate to it at [http://localhost:8080/installer.php](http://localhost:8080/installer.php)
   in order to install the exported website into the running WordPress container by following
   and answering its prompts, as suggested:
   - In `Step: Deployment; Section: Setup; Tab: Database` supply the following values:
     - Action: `Empty Database`
     - Host: `db` (see: `docker-compose.yml:WORDPRESS_DB_HOST`)
     - Database: `wordpress` (see: `docker-compose.yml:MYSQL_DATABASE`)
     - User: `wordpress` (see: `docker-compose.yml:WORDPRESS_DB_USER`)
     - Password: `wordpress` (see: `docker-compose.yml:WORDPRESS_DB_PASSWORD`)
   - Proceed to `Validate`, accept the terms, then `Next` to the next step...
   - `Step: Test Site`
     - Complete the process by pressing on the button to navigate to the
       [Admin Login](http://localhost:8080/wp-admin/admin.php)
       page, and ... wait until you see the login dialog.
     - Log in as the administrator user (i.e., the usual credential should work).
       Allow some time to complete and give confirmation / result summary of the
       site migration.
   - Confirm Independence from Production Website
     - At least initially, consider confirming proper operation of the new website in
       a completely "local" operating mode (i.e., after dropping your connection to the
       internet / production system) in order to gain confidence that making changes
       in this system will not have any effect on the production system.

Once the above is complete:
- The `docker/volumes/wordpress` folder contains the activated replica of the production
  site's filesystem
- The database managed by the `db` container contains the activated replica of the production
  site's database

These resources should not be modified other than by the running container, or cleared manually
in preparation for a subsequent update / installation, as described above.

Now the set of running Docker containers can be used as a sort of "staging" environment,
accessed via the URL [http://localhost:8080](http://localhost:8080), and should behave
similarly to the production environment in response to changes.

## Notes

I've noticed response time for pretty much all operations within the dockerized instance
is _very_ slow - at least 20 seconds or so for every click or operation!

As of late August 2023, after hours spent trying to discover a more precise source of the
slowness, the best solution in hand is to completely disable the "Wordfence" plugin.  Doing
so brings the response time down to about 3 seconds for most operations.

## Project Name

I used ChatGPT to help come up with the name for this project.  For posterity, I'm including
its summary of how the name fits and why it was chosen:

> "wpforge" is a great choice for your project name. It evokes a sense of craftsmanship and
> creation around WordPress, which aligns well with the essence of your project. The name
> suggests the idea of forging or shaping WordPress resources in a controlled environment,
> which aligns with your goal of creating a safe and controlled space for experimentation
> and changes.
>
> Additionally, "wpforge" subtly hints at the idea of using Docker containers to forge or
> shape the WordPress environment, which resonates with your approach of utilizing Docker
> to create a controlled staging environment.
>
> Overall, "wpforge" effectively captures the core aspects of your project: creating,
> crafting, and shaping a controlled WordPress environment for safe experimentation and changes.