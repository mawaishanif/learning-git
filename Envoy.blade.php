@servers(['web' => 'deployer@165.227.216.24'])

@setup
    $repository = "https://mawaishanif:{$password}@github.com/mawaishanif/learning-git.git";
    $releases_dir = '/var/www/app/releases';
    $app_dir = '/var/www/app';
    $release = date('Y_m_d_H_i_s');
    $new_release_dir = $releases_dir .'/'. $release;
@endsetup

@story('deploy')
    clone_repository
    run_composer
    update_symlinks
    writeable
    migrate
    restart_queues
@endstory

@task('clone_repository')
    echo 'Cloning repository'
    echo {{ $repository }} >> repo.txt
    [ -d {{ $releases_dir }} ] || mkdir {{ $releases_dir }}
    git clone --depth 1 {{ $repository }} {{ $new_release_dir }}
    cd {{ $new_release_dir }}
    git reset --hard {{ $commit }}
@endtask

@task('writeable')
    echo 'make bootstrap/cache writeable ...'
    cd {{ $new_release_dir }}
    chgrp -R www-data bootstrap/cache
    chmod -R g+w bootstrap/cache
@endtask

@task('migrate')
    echo "migrating database ..."
    cd {{ $new_release_dir }}
    php artisan migrate --force -q
@endtask

@task('run_composer')
    echo 'Linking .env file'
    ln -nfs {{ $app_dir }}/.env {{ $new_release_dir }}/.env

    echo "Starting deployment ({{ $release }})"
    cd {{ $new_release_dir }}
    composer install --prefer-dist --no-scripts -q -o
@endtask

@task('update_symlinks')
    echo "Linking storage directory"
    rm -rf {{ $new_release_dir }}/storage
    ln -nfs {{ $app_dir }}/storage {{ $new_release_dir }}/storage

    echo 'Linking current release'
    ln -nfs {{ $new_release_dir }} {{ $app_dir }}/current

    echo 'Symling storage to public folder'
    cd {{ $new_release_dir }} && php artisan storage:link
@endtask

@task('restart_queues')
    cd {{ $new_release_dir }}
    php artisan queue:restart
@endtask
