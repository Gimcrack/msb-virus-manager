@servers(['localhost' => '127.0.0.1'])

@task('cleanup')
    cd /home/vagrant/Code/msb-virus-manager/storage/logs
    find . -name "*.log" -size +100M -delete
@endtask
