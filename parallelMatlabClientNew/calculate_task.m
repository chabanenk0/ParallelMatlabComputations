function calculate_task(measurefunction,infilename,wind,tendwind,resid)
% ������� �������� ������� ����
% measurefunction - ����� ������ ������� �������� ���� (�����������
%     ���������� y_fragment ��� ������� ������ �������� ���� ��� ��������
% infilename - ������� ����
% wind - ���� (����� �����
% tendwind - ������� ���������� �������� ����
% resid - id ��������������� ���������� ����

y=dlmread(infilename);
if ((tendwind-wind>0)&&(tendwind<length(y)))
    y_fragment=y(tendwind-wind:tendwind);
    eval(measurefunction);
    send_task_result(resid,tendwind,c1,c2,c3);
else
    error;
end