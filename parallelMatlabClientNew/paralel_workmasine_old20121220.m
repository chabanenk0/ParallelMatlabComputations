function paralel_workmasine_new;
% Программа, работающая на рабочей машине (запускается на каждой
% машине/ядре)
servershare=' \\\\192.168.2.120\\SharedDocs';
%serverweb='http://prognoz.sys-admin.dp.ua:80';
serverweb='http://prognoz.ck.ua:80';
%serverweb='http://127.0.0.1:80';
%serverweb='http://192.168.2.168:80';
taskpath='F:\!diskd\work_matlab\PredictionBenchmark\20110704_MarkovNewalgorithm_testlen\';
global path_to_rar;
if (isempty(path_to_rar))
    path_to_rar='C:\Program Files\WinRAR\';
end


global server_data_url;
server_data_url='ftp://prognoz.sys-admin.dp.ua/pub/';
global path_to_wget;
path_to_wget='wget\';
global path_to_curl;
path_to_curl='curl\';
%uid=1;% идентификатор пользователя, который 
%methodsfolder='C:\Users\Андрей\Dropbox\ParallelMatlabServer\ParallelMatlab20MatlabClient\';
%methodsfolder='d:\20120611\ParallelMatlab20MatlabClient\';
%datafolder =  'C:\Users\Андрей\Dropbox\ParallelMatlabServer\ParallelMatlab20MatlabClient\data\';
%datafolder = 'd:\20120611\ParallelMatlab20MatlabClient\data\';

%methodsfolder='D:\dropboxfolder\Dropbox\ParallelMatlabServer\ParallelMatlab20MatlabClient\';
%datafolder =  'D:\dropboxfolder\Dropbox\ParallelMatlabServer\ParallelMatlab20MatlabClient\data\';
methodsfolder=pwd;
datafolder=[methodsfolder,'data\'];

addpath(pwd);

curdataid=0;

while 1
    try
        [uid,pid]=get_uid_pid();
        data=urlread(strcat(serverweb,['/ParalelMatlabServer2/processreg.php?uid=' num2str(uid) '&platformid=1','&pid=' num2str(pid)]));
        [id,COUNT,ERRMSG,NEXTINDEX]=sscanf(data,'%d',1);
        id=id(1);
        pid=id;
    catch
        uid=input('Enter your user ID (number of the user in users table):');
        data=urlread(strcat(serverweb,['/ParalelMatlabServer2/processreg.php?uid=' num2str(uid) '&platformid=1']));
        [id,COUNT,ERRMSG,NEXTINDEX]=sscanf(data,'%d',1);
        id=id(1);
        pid=id;
        fp01=fopen('get_uid_pid.m','w');
        fprintf(fp01,'function [uid,pid]=get_uid_pid();\n');
        fprintf(fp01,'%%This m-file saves uid and pid for this machine\n\n');
        fprintf(fp01,'uid=%d;\n',uid);
        fprintf(fp01,'pid=%d;\nend\n',pid);
        fclose(fp01);
        rehash;
    end
data=data(NEXTINDEX:length(data));
[num_res,COUNT,ERRMSG,NEXTINDEX]=sscanf(data,'%d',1);
data=data(NEXTINDEX:length(data));
curdir=pwd;
cd (methodsfolder);
for i=1:num_res
    %name=['method' num2str(i)];
    [methodid,COUNT,ERRMSG,NEXTINDEX]=sscanf(data,'%d',1);
    %id=id(1);
    data=data(NEXTINDEX:length(data));
    [name,COUNT,ERRMSG,NEXTINDEX]=sscanf(data,'%s',1);
    data=data(NEXTINDEX:length(data));
    % name=data(i,2);
    try
       cd(name);
       addpath(pwd);
       cd ('..');
    catch
        urlwrite(strcat(serverweb,['/ParalelMatlabServer2/methodget.php?id=' num2str(methodid)]),[name '.rar']);
        mkdir(name);        
        system(['"' path_to_rar 'rar" e ' name '.rar *.* ' name '/']);
        cd (name);
        % распаковываем и добавляем путь...
        addpath(pwd);
        cd ('..');
    end
end


%cd(methodsfolder);
if (isdir(datafolder))
    cd(datafolder);
else
    cd(methodsfolder);
    mkdir('data'); %if you have different location you should either create directories manually or rewrite this actions
end


%cd(taskpath);
    %addpath(genpath('m:\'));
    %rehash toolbox;
    %data = wwwread(strcat(serverweb,'/ParalelMatlabServerNew/gettask.php'));
    data=urlread([serverweb '/ParalelMatlabServer2/gettask.php?curdataid=' num2str(curdataid)]);
    
    [num_task,COUNT,ERRMSG,NEXTINDEX]=sscanf(data,'%d',1);
    data=data(NEXTINDEX:length(data));
    lastenter=strfind(data,sprintf('%c','\n'));
    udata=uint8(data);
    lastenter=find(udata==13);
    lastenter=lastenter(1);
    NEXTINDEX=lastenter+1;
    %[command,COUNT,ERRMSG,NEXTINDEX]=sscanf(data,'%s',1);
    command=data(1:lastenter);
    COUNT=lastenter;
    data=data(NEXTINDEX:length(data));
    [datafile,COUNT,ERRMSG,NEXTINDEX]=sscanf(data,'%s',1);
    data=data(NEXTINDEX:length(data));
    [dataid,COUNT,ERRMSG,NEXTINDEX]=sscanf(data,'%d',1);
    data=data(NEXTINDEX:length(data));
    if isempty(num_task)
        if (curdataid~=0)
            % сюда вписать запакование и отправку...
            cd (lastdatafile);
            load('dir.mat');
            archfilename=[lastdatafile '_computed_uid' num2str(uid) '_pid_' num2str(id) '.rar'];
            archivenewfiles(filesexcept,archfilename);
            sendnewfiles(serverweb,archfilename,id,curdataid,num_task,uid);
            % Закомментировал, бо отправляет 100мб файлы. Нужно отлаживать.
            curdataid=0;
            cd ('..')
            continue;
        else
            curdataid=dataid;
            fprintf(1,'Waiting for a new job at server.\nPause 10 seconds...');
            pause(1);
            continue;
        end
    else 
        index=num_task;
        curdataid=dataid;
        %command=data{2};
        fprintf(1,'Got a new job # %d\n',index);
        fprintf(1,'Executing command: %s\n',command);
    end
    % нужно переместить эту процедуру в пхп-часть. 
    lastslash=find(datafile=='/');
    lastslash=lastslash(length(lastslash));
    datafile=datafile(lastslash+1:length(datafile));
    datafile=strrep(datafile,'.rar','');
    lastdatafile=datafile;

    %data=data2cell(data);
    try
       cd(datafile);
       %addpath(pwd);
       %cd ('..');
    catch
        urlwrite(strcat(serverweb,['/ParalelMatlabServer2/dataget.php?id=' num2str(dataid)]),[datafile '.rar']);
        mkdir(datafile);
        system(['"' path_to_rar 'rar" e ' datafile '.rar *.* ' datafile '/']);
        cd (datafile);
        filesexcept=dir;
        save('dir.mat','filesexcept');
        % распаковываем и добавляем путь...
        %addpath(pwd);
        %cd ('..');
    end
    
    
    try
        eval(command);
        %s=wwwread(strcat(serverweb,strcat('/ParalelMatlabServerNew/done.php?result=1&id=',num2str(index))));
        s=urlread(strcat(serverweb,strcat('/ParalelMatlabServer2/done.php?result=1&id=',num2str(index))));
        fprintf(1,'Execution ok.\n');
    catch
        %s=wwwread(strcat(serverweb,strcat('/ParalelMatlabServerNew/done.php?result=0&id=',num2str(index))));
        s=urlread(strcat(serverweb,strcat('/ParalelMatlabServer2/done.php?result=0&id=',num2str(index))));
        fprintf(1,'Execution failed.\n');
    end
    %data=yahoo(serverweb);
    %data=fetch(sock,'d');
    %close(sock);
    cd('..');
    fprintf(1,'Press any key...');
    %pause;
end


%system(['"' path_to_rar '"rar a ' filename ' ' addfiles ]);