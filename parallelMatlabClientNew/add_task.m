function [taskid,taskgroupid]=add_task(command,methodid,dataid,adduserid,predictminutes,taskgroupdata,server)
% Функция, добавляющая новое задание на сервер.
% Параметры:
% command - текст команды...
% methodid - ид метода прогнозирования
% dataid - ид входного пакета данных
% adduserid - ид пользователя, добавляющего задание
% predictminutes - прогнозируемое время рассчета в минутах
% taskgroupdata - ид группы заданий, результат которой стает входным
% пакетом для данной команды
% server - сервер, куда добавляется задание. По умолчанию http://127.0.0.1:80/
%
% Идентификаторы и коды заданий, данных и т.д. должны быть определены
% программистом самостоятельно, используя веб-сайт управления системой.
if (nargin<1)
    fprintf(1,'Command unspecified. Unable to continue.');
else
    if (nargin<2)
        methodid=1; % по умолчанию - без метода
    end
    if (nargin<3)
        dataid=1; % по умолчанию - без пакета данных
    end
    if (nargin<4)
        adduserid=1; % по умолчанию - админ
    end
    if (nargin<5)
        predictminutes=60; % по умолчанию - час на рассчет
    end
    if (nargin<5)
        taskgroupdata=0; % по умолчанию - без зависимой группы заданий
    end
    if (nargin<6)
        server='http://127.0.0.1:80/'; % по умолчанию - локальный сервер
    end
end

url=strcat(server,['/ParalelMatlabServer2/taskaddmany2.php']);
%$tasks=$_REQUEST['tasks'];
%$userid=$_REQUEST['userid'];
%$platformid=$_REQUEST['platformid'];
%$dataid=$_REQUEST['archiveid'];
%$methodid=$_REQUEST['methodid'];
%$processid=$_REQUEST['processid'];
%$curgroupname=$_REQUEST['curgroupname'];
%$taskgroupdata=$_REQUEST['taskgroupdata'];
%$filename=$_REQUEST['infile'];
%$predictminutes=$_REQUEST['predictminutes'];
params = {'tasks',command, 'userid',num2str(adduserid),'platformid','1',...
    'archiveid',num2str(dataid),'methodid',num2str(methodid),'taskgroupdata',...
    num2str(taskgroupdata),'filename','dj.txt','predictminutes',num2str(predictminutes)}; % 20130116 пробую без таскайди
data=urlread(url,'POST',params);
[taskid,COUNT,ERRMSG,NEXTINDEX]=sscanf(data,'%d',1);
data=data(NEXTINDEX:length(data));
[taskgroupid,COUNT,ERRMSG,NEXTINDEX]=sscanf(data,'%d',1);
data=data(NEXTINDEX:length(data));
